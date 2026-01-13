<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hero extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'roles' => 'array',
        'pros' => 'array',
        'cons' => 'array',
    ];

    public function abilities()
    {
        return $this->hasMany(Ability::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}