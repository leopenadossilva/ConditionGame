<?php

namespace Database\Seeders;

use App\Models\Enemy;
use App\Models\Player;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        Player::query()->firstOrCreate(
            ['name' => 'Leo'],
            ['hp' => 100, 'max_hp' => 100, 'level' => 1, 'xp' => 0, 'gold' => 25],
        );

        $enemies = [
            [
                'name' => 'Slime',
                'description' => 'A wobbling green blob. Harmless, mostly.',
                'hp' => 10, 'max_hp' => 10, 'level' => 1,
                'xp_reward' => 5, 'gold_reward' => 2,
            ],
            [
                'name' => 'Goblin',
                'description' => 'Small, mean, and carrying a chipped dagger.',
                'hp' => 18, 'max_hp' => 18, 'level' => 2,
                'xp_reward' => 12, 'gold_reward' => 6,
            ],
            [
                'name' => 'Cave Bat',
                'description' => 'Fast, erratic, and allergic to lantern light.',
                'hp' => 14, 'max_hp' => 14, 'level' => 2,
                'xp_reward' => 9, 'gold_reward' => 4,
            ],
            [
                'name' => 'Stone Golem',
                'description' => 'Slow and ancient. Its fists end fights in one swing.',
                'hp' => 60, 'max_hp' => 60, 'level' => 5,
                'xp_reward' => 40, 'gold_reward' => 25,
            ],
        ];

        foreach ($enemies as $enemy) {
            Enemy::query()->firstOrCreate(['name' => $enemy['name']], $enemy);
        }
    }
}
