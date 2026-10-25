<?php

namespace App\Game;

use App\Game\Tools\GameTool;
use InvalidArgumentException;

class ToolRegistry
{
    /** @var array<string, GameTool> */
    private array $tools = [];

    /**
     * @param  iterable<GameTool>  $tools
     */
    public function __construct(iterable $tools = [])
    {
        foreach ($tools as $tool) {
            $this->register($tool);
        }
    }

    public function register(GameTool $tool): void
    {
        $this->tools[$tool->name()] = $tool;
    }

    public function has(string $name): bool
    {
        return isset($this->tools[$name]);
    }

    /**
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>
     */
    public function call(string $name, array $arguments = []): array
    {
        if (! $this->has($name)) {
            throw new InvalidArgumentException("Unknown game tool [{$name}].");
        }

        return $this->tools[$name]->handle($arguments);
    }

    /**
     * Tool definitions in the shape an LLM tool-calling API expects.
     *
     * @return list<array<string, mixed>>
     */
    public function definitions(): array
    {
        return array_values(array_map(
            fn (GameTool $tool) => [
                'type' => 'function',
                'function' => [
                    'name' => $tool->name(),
                    'description' => $tool->description(),
                    'parameters' => $tool->parameters(),
                ],
            ],
            $this->tools,
        ));
    }

    /**
     * Lightweight, agent-facing catalog for the UI.
     *
     * @return list<array{name: string, description: string}>
     */
    public function catalog(): array
    {
        return array_values(array_map(
            fn (GameTool $tool) => [
                'name' => $tool->name(),
                'description' => $tool->description(),
            ],
            $this->tools,
        ));
    }
}
