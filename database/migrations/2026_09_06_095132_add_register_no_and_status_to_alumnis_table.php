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
        Schema::table('alumnis', function (Blueprint $table) {
            $table->string('register_no')->nullable()->unique()->after('id');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('alumnis', function (Blueprint $table) {
            $table->dropColumn(['register_no', 'status']);
        });
    }
};
