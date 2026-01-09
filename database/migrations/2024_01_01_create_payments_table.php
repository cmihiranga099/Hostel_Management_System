<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            // ...existing code...
            $table->string('payment_method_id')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('LKR');
            $table->enum('status', ['pending', 'succeeded', 'failed', 'cancelled', 'refunded'])->default('pending');
            // ...existing code...
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};