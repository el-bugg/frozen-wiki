<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'stats' => 'array',
        'components' => 'array',
        'popular_heroes' => 'array',
        'recipe_cost' => 'boolean',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}