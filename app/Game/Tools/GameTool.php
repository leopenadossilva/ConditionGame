<?php

namespace App\Game\Tools;

interface GameTool
{
    /**
     * Stable tool name exposed to the agent, e.g. "enemies_get".
     */
    public function name(): string;

    /**
     * One-line description the agent uses to decide when to call this tool.
     */
    public function description(): string;

    /**
     * JSON Schema describing the tool's arguments.
     *
     * @return array<string, mixed>
     */
    public function parameters(): array;

    /**
     * Execute the tool and return JSON-serializable data.
     *
     * @param  array<string, mixed>  $arguments
     * @return array<string, mixed>
     */
    public function handle(array $arguments): array;
}
