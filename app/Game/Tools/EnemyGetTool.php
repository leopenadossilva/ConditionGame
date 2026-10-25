<?php

namespace App\Game\Tools;

use App\Models\Enemy;

class EnemyGetTool implements GameTool
{
    public function name(): string
    {
        return 'enemy_get';
    }

    public function description(): string
    {
        return 'Look up one enemy by name (partial names are fine). Returns its stats and rewards.';
    }

    public function parameters(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'name' => [
                    'type' => 'string',
                    'description' => 'Enemy name or part of it, e.g. "goblin".',
                ],
            ],
            'required' => ['name'],
            'additionalProperties' => false,
        ];
    }

    public function handle(array $arguments): array
    {
        $name = trim((string) ($arguments['name'] ?? ''));

        if ($name === '') {
            return ['enemy' => null, 'error' => 'A name is required.'];
        }

        $enemy = Enemy::query()
            ->whereRaw('lower(name) like ?', ['%'.strtolower($name).'%'])
            ->orderBy('level')
            ->first();

        return [
            'enemy' => $enemy?->toGameArray(),
            'error' => $enemy === null ? "No enemy matches \"{$name}\"." : null,
        ];
    }
}
