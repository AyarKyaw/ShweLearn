<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumni_id')->constrained('alumnis')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('MMK');
            $table->string('payment_method')->nullable(); // e.g., 'KBZPay', 'CB_Bank_MMQR'
            $table->string('transaction_id')->unique()->nullable();
            $table->string('status')->default('pending'); // pending, success, failed
            $table->json('gateway_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni_payments');
    }
};
