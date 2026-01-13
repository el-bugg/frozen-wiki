<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Hero
    public function hero()
    {
        return $this->belongsTo(Hero::class);
    }

    // Relasi ke Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    
    // --- PERBAIKAN DI SINI ---
    // Gunakan 'morphMany' agar dia mencari 'likeable_id' & 'likeable_type'
    // Bukannya mencari 'post_id'
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}