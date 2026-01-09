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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('hostel_packages')->onDelete('set null');
            $table->foreignId('hostel_package_id')->nullable()->constrained('hostel_packages')->onDelete('set null'); // For compatibility
            
            // Booking Details
            $table->string('booking_reference')->unique();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('duration_days')->nullable();
            
            // Pricing
            $table->decimal('amount', 10, 2)->nullable(); // For compatibility
            $table->decimal('total_amount', 10, 2);
            
            // Status Fields
            $table->string('status')->default('pending'); // For compatibility
            $table->string('booking_status')->default('pending'); // pending, confirmed, cancelled, completed, checked_in, checked_out
            $table->string('payment_status')->default('pending'); // pending, paid, failed, refunded, partial
            
            // Payment Information
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // Personal Information
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('student_id')->nullable();
            $table->string('university')->nullable();
            
            // Additional Details
            $table->text('special_requests')->nullable();
            $table->json('student_details')->nullable();
            
            // Timestamps
            $table->timestamp('booked_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'booking_status']);
            $table->index(['hostel_id', 'check_in_date']);
            $table->index(['booking_reference']);
            $table->index(['payment_status']);
            $table->index(['status']);
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
        Schema::dropIfExists('bookings');
    }
};