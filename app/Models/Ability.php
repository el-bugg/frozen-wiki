<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'mana_cost' => 'array',
        'cooldown' => 'array',
    ];

    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }
}