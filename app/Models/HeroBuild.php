<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroBuild extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'early_game' => 'array',
        'mid_game' => 'array',
        'late_game' => 'array',
        'situational' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function hero() { return $this->belongsTo(Hero::class); }
}