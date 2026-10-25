<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemy extends Model
{
    protected $fillable = [
        'name', 'description', 'hp', 'max_hp', 'level', 'xp_reward', 'gold_reward',
    ];

    protected function casts(): array
    {
        return [
            'hp' => 'integer',
            'max_hp' => 'integer',
            'level' => 'integer',
            'xp_reward' => 'integer',
            'gold_reward' => 'integer',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toGameArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'hp' => $this->hp,
            'max_hp' => $this->max_hp,
            'level' => $this->level,
            'xp_reward' => $this->xp_reward,
            'gold_reward' => $this->gold_reward,
        ];
    }
}
