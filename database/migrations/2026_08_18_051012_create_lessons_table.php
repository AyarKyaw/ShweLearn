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
       // 1. Lessons Table
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type', ['video', 'quiz', 'article'])->default('video'); // Type flag
            $table->string('video_url')->nullable();
            $table->longText('content')->nullable(); // For articles or extra text
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Questions Table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->text('question_text');
            $table->enum('type', ['multiple_choice', 'boolean'])->default('multiple_choice');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Question Options Table
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
