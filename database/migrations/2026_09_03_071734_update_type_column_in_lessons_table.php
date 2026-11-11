<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change type column to string/varchar
        DB::statement("ALTER TABLE lessons MODIFY COLUMN `type` VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE lessons MODIFY COLUMN `type` ENUM('article', 'video', 'document', 'quiz') NOT NULL");
    }
};