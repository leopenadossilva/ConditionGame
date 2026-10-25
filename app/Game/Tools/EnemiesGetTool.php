<?php

namespace App\Game\Tools;

use App\Models\Enemy;

class EnemiesGetTool implements GameTool
{
    public function name(): string
    {
        return 'enemies_get';
    }

    public function description(): string
    {
        return 'List every enemy in the world with its level, current HP, and rewards.';
    }

    public function parameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'max_level' => [
                    'type' => 'integer',
                    'description' => 'Only include enemies at or below this level.',
                ],
            ],
            'additionalProperties' => false,
        ];
    }

    public function handle(array $arguments): array
    {
        $query = Enemy::query()->orderBy('level')->orderBy('name');

        if (isset($arguments['max_level'])) {
            $query->where('level', '<=', (int) $arguments['max_level']);
        }

        $enemies = $query->get()->map->toGameArray()->all();

        return [
            'count' => count($enemies),
            'enemies' => $enemies,
        ];
    }
}
