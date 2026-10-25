<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['name', 'hp', 'max_hp', 'level', 'xp', 'gold'];

    protected function casts(): array
    {
        return [
            'hp' => 'integer',
            'max_hp' => 'integer',
            'level' => 'integer',
            'xp' => 'integer',
            'gold' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->orderBy('id')->firstOrFail();
    }

    public function toGameArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'hp' => $this->hp,
            'max_hp' => $this->max_hp,
            'level' => $this->level,
            'xp' => $this->xp,
            'gold' => $this->gold,
        ];
    }
}
