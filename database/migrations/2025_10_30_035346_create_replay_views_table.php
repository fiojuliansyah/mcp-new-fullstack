<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('replay_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('replay_video_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('view_number')->default(1);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->integer('duration_watched')->nullable()->comment('in seconds'); 
            $table->integer('last_position')->nullable()->comment('last watched position in seconds');
            $table->unique(['replay_video_id', 'user_id', 'view_number']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('replay_views');
    }
};
