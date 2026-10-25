<?php

namespace App\Providers;

use App\Game\ToolRegistry;
use App\Game\Tools\EnemiesGetTool;
use App\Game\Tools\EnemyGetTool;
use App\Game\Tools\PlayerGetTool;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ToolRegistry::class, fn () => new ToolRegistry([
            new EnemiesGetTool,
            new EnemyGetTool,
            new PlayerGetTool,
        ]));
    }

    public function boot(): void
    {
        //
    }
}
