<?php

namespace App\Http\Controllers;

use App\Game\ToolRegistry;
use App\Models\Enemy;
use App\Models\Player;
use Illuminate\Http\JsonResponse;

class GameController extends Controller
{
    public function __construct(private ToolRegistry $tools) {}

    public function state(): JsonResponse
    {
        return response()->json([
            'player' => Player::current()->toGameArray(),
            'enemies' => Enemy::query()->orderBy('level')->get()->map->toGameArray(),
        ]);
    }

    public function tools(): JsonResponse
    {
        return response()->json(['tools' => $this->tools->catalog()]);
    }
}
