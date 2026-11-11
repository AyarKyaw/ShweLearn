<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename column and make it nullable first
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['course_category_id']);
            $table->renameColumn('course_category_id', 'category_id');
        });

        // 2. Set any category_id values that don't exist in the 'categories' table to NULL (or delete orphaned courses)
        DB::statement('UPDATE courses SET category_id = NULL WHERE category_id NOT IN (SELECT id FROM categories)');

        // 3. Make column nullable (if it isn't already) and attach foreign key constraint
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->renameColumn('category_id', 'course_category_id');
            $table->foreign('course_category_id')->references('id')->on('course_categories')->onDelete('cascade');
        });
    }
};