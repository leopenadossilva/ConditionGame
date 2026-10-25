<?php

namespace Tests\Feature;

use Database\Seeders\GameSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GamePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_game_page_renders_with_live_state(): void
    {
        $this->seed(GameSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Game')
                ->where('player.name', 'Leo')
                ->has('enemies', 4));
    }
}
