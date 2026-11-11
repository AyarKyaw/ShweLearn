<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the leftover partial table if it exists
        Schema::dropIfExists('course_payments');

        Schema::create('course_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('student_profiles')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('MMK');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->unique()->nullable();
            $table->string('status')->default('pending');
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_payments');
    }
};