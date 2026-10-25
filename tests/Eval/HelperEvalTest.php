<?php

namespace Tests\Eval;

use App\Game\AgentService;
use App\Game\ToolRegistry;
use Database\Seeders\GameSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Agent evals: fixed prompts -> expected tool usage.
 *
 * These are the guardrail against the helper confidently making things up.
 * When we add an LLM provider, record its calls and keep these as the baseline.
 */
class HelperEvalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['agent.api_key' => null]);
        $this->seed(GameSeeder::class);
    }

    /**
     * @return array<string, array{string, list<string>, string}>
     */
    public static function prompts(): array
    {
        return [
            'lists enemies' => [
                'What enemies are out there?',
                ['enemies_get'],
                'Slime',
            ],
            'looks up one enemy by name' => [
                'Tell me about the golem',
                ['enemy_get'],
                '60/60 HP',
            ],
            'reads player status' => [
                'How much gold do I have?',
                ['player_get'],
                '25 gold',
            ],
            'turns fight advice into an enemy lookup' => [
                'Who should I fight first?',
                ['enemies_get'],
                'Stone Golem',
            ],
            'greets without inventing a tool call' => [
                'hey there',
                [],
                'ask about enemies',
            ],
        ];
    }

    /**
     * @param  list<string>  $expectedTools
     */
    #[DataProvider('prompts')]
    public function test_helper_selects_expected_tools(string $prompt, array $expectedTools, string $replyContains): void
    {
        $result = app(AgentService::class)->chat($prompt);

        $used = array_column($result['tool_calls'], 'name');

        $this->assertSame($expectedTools, $used, "Unexpected tools for: {$prompt}");
        $this->assertStringContainsString($replyContains, $result['reply']);
    }

    public function test_helper_never_calls_a_tool_outside_the_registry(): void
    {
        $known = array_column(app(ToolRegistry::class)->catalog(), 'name');

        foreach (self::prompts() as [$prompt]) {
            $result = app(AgentService::class)->chat($prompt);

            foreach ($result['tool_calls'] as $call) {
                $this->assertContains($call['name'], $known, "Hallucinated tool [{$call['name']}] for: {$prompt}");
            }
        }
    }
}
