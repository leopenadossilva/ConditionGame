<?php

namespace Tests\Unit;

use App\Game\ToolRegistry;
use App\Game\Tools\EnemiesGetTool;
use App\Game\Tools\PlayerGetTool;
use InvalidArgumentException;
use Tests\TestCase;

class ToolRegistryTest extends TestCase
{
    public function test_it_exposes_definitions_in_tool_calling_shape(): void
    {
        $registry = new ToolRegistry([new EnemiesGetTool, new PlayerGetTool]);

        $names = array_map(fn (array $tool) => $tool['function']['name'], $registry->definitions());

        $this->assertSame(['enemies_get', 'player_get'], $names);
        $this->assertSame('enemies_get', $registry->catalog()[0]['name']);
    }

    public function test_it_rejects_unknown_tools(): void
    {
        $registry = new ToolRegistry;

        $this->expectException(InvalidArgumentException::class);

        $registry->call('dragons_get');
    }
}
