<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // =================================================================
    // RELASI UNTUK FROZEN WIKI (Tambahkan bagian ini)
    // =================================================================

    // User bisa punya banyak Postingan
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // User bisa punya banyak Komentar
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // User bisa punya banyak Like
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function getProfileUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

       
        $heroId = ($this->id % 120) + 1; 
        
        $heroShortName = \Illuminate\Support\Facades\Cache::remember("user_hero_icon_{$this->id}", 86400, function() use ($heroId) {
            return \App\Models\Hero::find($heroId)?->short_name ?? 'antimage';
        });
        
        return "https://cdn.cloudflare.steamstatic.com/apps/dota2/images/dota_react/heroes/icons/{$heroShortName}.png";
    }

}