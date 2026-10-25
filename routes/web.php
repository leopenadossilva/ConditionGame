<?php

use App\Models\Enemy;
use App\Models\Player;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Game', [
        'player' => Player::current()->toGameArray(),
        'enemies' => Enemy::query()->orderBy('level')->get()->map->toGameArray(),
    ]);
})->name('game');
