<?php

namespace App\Game\Tools;

use App\Models\Player;

class PlayerGetTool implements GameTool
{
    public function name(): string
    {
        return 'player_get';
    }

    public function description(): string
    {
        return 'Read the current player state: name, HP, level, XP, and gold.';
    }

    public function parameters(): array
    {
        return [
            'type' => 'object',
            'properties' => (object) [],
            'additionalProperties' => false,
        ];
    }

    public function handle(array $arguments): array
    {
        return ['player' => Player::current()->toGameArray()];
    }
}
