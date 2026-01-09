<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            
            // Payment Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('LKR');
            $table->string('payment_method'); // visa, mastercard, amex, etc.
            $table->string('payment_status')->default('pending'); // pending, processing, completed, failed, refunded
            $table->string('status')->default('pending'); // For compatibility
            
            // References
            $table->string('payment_reference')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('authorization_code')->nullable();
            
            // Gateway Integration
            // ...existing code...
            $table->string('payment_method_id')->nullable();
            $table->json('gateway_response')->nullable();
            // ...existing code...
            
            // Card Information (Secure)
            $table->string('card_last_four', 4)->nullable();
            $table->string('card_type')->nullable(); // visa, mastercard, amex
            
            // Timestamps
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'payment_status']);
            $table->index(['booking_id']);
            $table->index(['payment_reference']);
            $table->index(['transaction_id']);
            $table->index(['payment_status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};