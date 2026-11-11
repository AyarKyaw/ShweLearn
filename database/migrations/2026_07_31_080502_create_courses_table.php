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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            
            // Foreign key linking to course_categories table
            $table->foreignId('course_category_id')
                  ->nullable()
                  ->constrained('course_categories')
                  ->nullOnDelete();

            $table->string('title');
            $table->integer('hour')->comment('Duration in hours'); // e.g., 40
            $table->decimal('price', 10, 2)->default(0.00); // e.g., 199.99
            $table->text('desc')->nullable(); // Course description
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};