<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesson_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            
            // Progress metrics (0 to 100)
            $table->integer('progress_percent')->default(0);
            $table->boolean('is_completed')->default(false);
            
            // Exercise/Quiz score tracking (Khan Academy mastery tiers)
            $table->integer('quiz_score')->nullable(); 
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']); // Prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_user');
    }
};
