<?php

namespace Tests\Feature;

use App\Game\Tools\EnemyGetTool;
use Database\Seeders\GameSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnemyGetToolTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(GameSeeder::class);
    }

    public function test_it_finds_an_enemy_by_partial_name(): void
    {
        $result = (new EnemyGetTool)->handle(['name' => 'gol']);

        $this->assertSame('Stone Golem', $result['enemy']['name']);
        $this->assertNull($result['error']);
    }

    public function test_it_reports_unknown_enemies_without_throwing(): void
    {
        $result = (new EnemyGetTool)->handle(['name' => 'dragon']);

        $this->assertNull($result['enemy']);
        $this->assertStringContainsString('No enemy matches', $result['error']);
    }

    public function test_it_requires_a_name(): void
    {
        $result = (new EnemyGetTool)->handle([]);

        $this->assertSame('A name is required.', $result['error']);
    }
}
