<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            // Drop old foreign key if it existed
            if (Schema::hasColumn('lessons', 'course_id')) {
                // Drop index/foreign key safely before dropping column
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }

            // Make section_id nullable so existing rows don't violate integrity constraints
            $table->foreignId('section_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn('section_id');
            $table->foreignId('course_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};