<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/game/state', [GameController::class, 'state']);
Route::get('/game/tools', [GameController::class, 'tools']);
Route::post('/agent/chat', [AgentController::class, 'chat']);
