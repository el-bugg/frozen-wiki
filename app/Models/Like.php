<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $guarded = ['id'];

    // Relasi Polymorphic (Agar Like bisa tahu dia nge-like Post atau Comment)
    public function likeable()
    {
        return $this->morphTo();
    }

    // Relasi ke User (Siapa yang nge-like)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}