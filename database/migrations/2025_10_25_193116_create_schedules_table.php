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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->text('topic')->nullable();
            $table->longtext('agenda')->nullable();
            $table->integer('type')->nullable();
            $table->integer('duration')->nullable();
            $table->string('timezone')->nullable();
            $table->string('password')->nullable();
            $table->datetime('time')->nullable();
            $table->json('settings')->nullable();
            $table->string('zoom_meeting_id')->nullable();
            $table->longtext('zoom_join_url')->nullable(); 
            $table->longtext('zoom_start_url')->nullable(); 
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
