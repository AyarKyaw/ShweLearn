<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add image to course_categories if it doesn't exist yet
        if (!Schema::hasColumn('course_categories', 'image')) {
            Schema::table('course_categories', function (Blueprint $table) {
                $table->string('image')->nullable()->after('name');
            });
        }

        // Only add image to courses if it doesn't exist yet
        if (!Schema::hasColumn('courses', 'image')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('image')->nullable()->after('title');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('course_categories', 'image')) {
            Schema::table('course_categories', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }

        if (Schema::hasColumn('courses', 'image')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
