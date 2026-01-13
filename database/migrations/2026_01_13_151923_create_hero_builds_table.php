<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_builds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hero_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // e.g. "Core Magic Build"
            
            // Simpan ID item dalam JSON (early, mid, late, situational)
            $table->json('early_game')->nullable();
            $table->json('mid_game')->nullable();
            $table->json('late_game')->nullable();
            $table->json('situational')->nullable();
            
            $table->integer('upvotes')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_builds');
    }
};
