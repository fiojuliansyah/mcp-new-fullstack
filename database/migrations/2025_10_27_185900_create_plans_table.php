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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->decimal('price', 10, 2);
            $table->longtext('description')->nullable();
            $table->enum('duration', ['week', 'month', 'year']);
            $table->string('duration_value');
            $table->string('device_limit')->nullable();
            $table->string('is_weekly_live_classes')->nullable();
            $table->string('is_materials')->nullable();
            $table->string('is_quizzes')->nullable();
            $table->string('replay_day')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
