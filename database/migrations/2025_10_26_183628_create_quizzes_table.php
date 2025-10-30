<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('form_id')->constrained()->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('estimated_time')->nullable()->comment('in minutes');
            $table->integer('attempts_time')->nullable()->comment('how many times a student can attempt');
            $table->integer('max_score')->nullable();
            $table->integer('total_question')->nullable();

            $table->enum('auto_mark', ['yes', 'no'])->default('yes');
            $table->dateTime('publish_date')->nullable();
            $table->enum('status', ['publish', 'draft'])->default('draft');

            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();

            $table->text('question')->nullable();
            $table->string('media_url')->nullable()->comment('image/video/audio attached to question');

            $table->enum('type_of_answer', ['multiple_choice', 'true_false', 'short_answer', 'essay'])
                  ->default('multiple_choice');

            $table->integer('answer_point_mark')->default(1);
            $table->json('answer')->nullable()->comment('store options for multiple choice');
            $table->string('correct_answer')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
        Schema::dropIfExists('quizzes');
    }
};
