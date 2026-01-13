<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dota_id')->unique();
            $table->string('code_name');
            $table->string('name_localized');
            $table->string('primary_attr');
            $table->string('attack_type');
            $table->json('roles')->nullable();
            $table->string('img_url')->nullable();
            $table->string('icon_url')->nullable();
            $table->string('video_url')->nullable();
            $table->text('lore')->nullable();
            
            // --- STATS LENGKAP ---
            $table->integer('base_health')->default(200);
            $table->decimal('base_health_regen', 8, 2)->nullable();
            $table->integer('base_mana')->default(75);
            $table->decimal('base_mana_regen', 8, 2)->nullable();
            $table->integer('base_str')->default(0);
            $table->integer('base_agi')->default(0);
            $table->integer('base_int')->default(0);
            $table->decimal('str_gain', 8, 2)->default(0);
            $table->decimal('agi_gain', 8, 2)->default(0);
            $table->decimal('int_gain', 8, 2)->default(0);
            $table->integer('attack_range')->default(0);
            $table->integer('move_speed')->default(0);
            $table->decimal('base_armor', 8, 2)->default(0);
            $table->integer('base_attack_min')->default(0);
            $table->integer('base_attack_max')->default(0);

            // Scraped Data
            $table->text('playstyle')->nullable(); 
            $table->json('pros')->nullable();
            $table->json('cons')->nullable();
            
            $table->decimal('pro_win', 5, 2)->default(0);
            $table->integer('pro_pick')->default(0);
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dota_id')->nullable();
            $table->string('key_name')->unique();
            $table->string('dname');
            $table->integer('cost')->default(0);
            $table->text('desc')->nullable();
            $table->text('lore')->nullable();
            $table->string('img_url')->nullable();
            
            // --- ITEM SPECS ---
            $table->json('stats')->nullable(); // Bonus stats (Damage, etc)
            $table->integer('cooldown')->nullable(); // CD
            $table->integer('mana_cost')->nullable(); // Mana
            
            $table->json('components')->nullable();
            $table->boolean('recipe_cost')->default(false);
            $table->json('popular_heroes')->nullable();
            $table->timestamps();
        });

        Schema::create('abilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hero_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('dname')->nullable();
            $table->text('desc')->nullable();
            $table->string('img_url')->nullable();
            $table->string('video_url')->nullable();
            $table->string('behavior')->nullable();
            $table->json('mana_cost')->nullable();
            $table->json('cooldown')->nullable();
            $table->timestamps();
        });

        // Community Tables (Tetap sama)
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('body');
            $table->foreignId('hero_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('views')->default(0);
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('likeable');
            $table->timestamps();
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('items');
        Schema::dropIfExists('heroes');
    }
};