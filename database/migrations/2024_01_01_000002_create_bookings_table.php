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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('hostel_package_id')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->string('booking_reference')->unique();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('duration_days')->nullable();
            $table->decimal('amount', 10, 2);
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2)->nullable();
            $table->decimal('service_fee', 10, 2)->default(1000.00);
            
            // Status fields
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'checked_in', 'checked_out'])->default('pending');
            $table->string('booking_status')->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'completed', 'failed', 'refunded', 'partial'])->default('pending');
            
            // Payment fields
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('transaction_id')->nullable();
            
            // Guest information
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone')->nullable();
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact')->nullable();
            
            // Student information
            $table->string('student_id')->nullable();
            $table->string('university')->nullable();
            $table->string('faculty')->nullable();
            $table->integer('year_of_study')->nullable();
            
            // Additional fields
            $table->json('student_details')->nullable();
            $table->text('special_requests')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('dietary_requirements')->nullable();
            
            // Package details (for compatibility)
            $table->string('package')->nullable();
            $table->string('duration')->nullable();
            
            // Timestamps
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['hostel_id', 'check_in_date']);
            $table->index('booking_reference');
            $table->index('payment_status');
            $table->index(['check_in_date', 'check_out_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};