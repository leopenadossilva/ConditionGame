<?php

namespace Tests\Feature;

use Database\Seeders\GameSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AgentChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['agent.api_key' => null]);
        $this->seed(GameSeeder::class);
    }

    public function test_it_calls_enemies_get_when_asked_about_enemies(): void
    {
        $response = $this->postJson('/api/agent/chat', [
            'message' => 'What enemies are out there?',
        ]);

        $response->assertOk()
            ->assertJsonPath('tool_calls.0.name', 'enemies_get')
            ->assertJsonPath('mode', 'heuristic');

        $this->assertStringContainsString('Slime', $response->json('reply'));
    }

    public function test_it_calls_player_get_when_asked_about_status(): void
    {
        $response = $this->postJson('/api/agent/chat', [
            'message' => 'How much health do I have?',
        ]);

        $response->assertOk()
            ->assertJsonPath('tool_calls.0.name', 'player_get');

        $this->assertStringContainsString('100/100 HP', $response->json('reply'));
    }

    public function test_it_treats_fight_questions_as_enemy_lookups(): void
    {
        $response = $this->postJson('/api/agent/chat', [
            'message' => 'Who should I fight first?',
        ]);

        $response->assertOk()
            ->assertJsonPath('tool_calls.0.name', 'enemies_get');

        $this->assertStringContainsString('Stone Golem', $response->json('reply'));
    }

    public function test_it_rejects_an_empty_message(): void
    {
        $this->postJson('/api/agent/chat', ['message' => ''])
            ->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }
}
