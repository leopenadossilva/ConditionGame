<?php

namespace App\Game;

use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Throwable;

class AgentService
{
    public function __construct(private ToolRegistry $tools) {}

    /**
     * Answer a player message, using game tools where needed.
     *
     * @return array{reply: string, tool_calls: list<array<string, mixed>>, mode: string}
     */
    public function chat(string $message): array
    {
        if ($this->usesLlm()) {
            try {
                return $this->chatWithLlm($message);
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $this->chatHeuristically($message);
    }

    private function usesLlm(): bool
    {
        return filled(config('agent.api_key'));
    }

    /**
     * @return array{reply: string, tool_calls: list<array<string, mixed>>, mode: string}
     */
    private function chatWithLlm(string $message): array
    {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt()],
            ['role' => 'user', 'content' => $message],
        ];

        $calls = [];

        for ($step = 0; $step < (int) config('agent.max_steps', 5); $step++) {
            $response = Http::withToken(config('agent.api_key'))
                ->timeout((int) config('agent.timeout', 30))
                ->post(rtrim(config('agent.base_url'), '/').'/chat/completions', [
                    'model' => config('agent.model'),
                    'messages' => $messages,
                    'tools' => $this->tools->definitions(),
                    'temperature' => 0.4,
                ])
                ->throw();

            $assistant = $response->json('choices.0.message', []);

            if (empty($assistant['tool_calls'])) {
                return [
                    'reply' => trim((string) ($assistant['content'] ?? '')) ?: 'I am not sure how to help with that yet.',
                    'tool_calls' => $calls,
                    'mode' => 'llm',
                ];
            }

            $messages[] = $assistant;

            foreach ($assistant['tool_calls'] as $call) {
                $name = $call['function']['name'] ?? '';
                $arguments = json_decode($call['function']['arguments'] ?? '{}', true) ?: [];
                $result = $this->tools->call($name, $arguments);

                $calls[] = [
                    'name' => $name,
                    'arguments' => $arguments,
                    'result' => $result,
                ];

                $messages[] = [
                    'role' => 'tool',
                    'tool_call_id' => $call['id'] ?? $name,
                    'content' => json_encode($result),
                ];
            }
        }

        return [
            'reply' => 'I gathered the data but could not settle on an answer. Try asking more specifically.',
            'tool_calls' => $calls,
            'mode' => 'llm',
        ];
    }

    private function systemPrompt(): string
    {
        $player = Player::current()->toGameArray();

        return <<<PROMPT
        You are Sage, the player's in-game helper in ConditionGame.
        You have tools that read live game data. Use them instead of guessing: never invent enemies, stats, or rewards.
        Keep answers short (1-3 sentences), concrete, and in the player's voice-free tone. No markdown tables.
        The player is {$player['name']}, level {$player['level']}, {$player['hp']}/{$player['max_hp']} HP, {$player['gold']} gold.
        PROMPT;
    }

    /**
     * Deterministic helper used when no LLM key is configured.
     *
     * @return array{reply: string, tool_calls: list<array<string, mixed>>, mode: string}
     */
    private function chatHeuristically(string $message): array
    {
        $normalized = strtolower($message);
        $calls = [];
        $lines = [];

        $wantsEnemies = (bool) preg_match('/enem|fight|battle|monster|kill|boss/', $normalized);
        $wantsPlayer = (bool) preg_match('/\b(player|health|hp|status|me|level|gold|xp)\b/', $normalized);

        if ($wantsEnemies) {
            $result = $this->tools->call('enemies_get');
            $calls[] = ['name' => 'enemies_get', 'arguments' => [], 'result' => $result];

            $names = array_map(
                fn (array $enemy) => "{$enemy['name']} (Lv{$enemy['level']}, {$enemy['hp']}/{$enemy['max_hp']} HP)",
                $result['enemies'],
            );

            $lines[] = 'Enemies out there: '.implode(', ', $names).'.';
        }

        if ($wantsPlayer || ! $wantsEnemies) {
            $result = $this->tools->call('player_get');
            $calls[] = ['name' => 'player_get', 'arguments' => [], 'result' => $result];

            $player = $result['player'];
            $lines[] = "{$player['name']} is level {$player['level']} with {$player['hp']}/{$player['max_hp']} HP, {$player['xp']} XP and {$player['gold']} gold.";
        }

        if (! $wantsEnemies && ! $wantsPlayer) {
            $lines[] = 'I can check the world for you — ask about enemies or your own status.';
        }

        return [
            'reply' => implode(' ', $lines),
            'tool_calls' => $calls,
            'mode' => 'heuristic',
        ];
    }
}
