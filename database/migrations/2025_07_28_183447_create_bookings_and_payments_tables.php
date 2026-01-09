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
        // Create bookings table
        if (!Schema::hasTable('bookings')) {
            Schema::create('bookings', function (Blueprint $table) {
                $table->id();
                $table->string('booking_reference')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('hostel_id')->nullable();
                $table->unsignedBigInteger('hostel_package_id')->nullable();
                $table->string('student_id')->nullable();
                $table->date('check_in_date');
                $table->date('check_out_date');
                $table->integer('duration')->default(1); // days
                $table->decimal('amount', 10, 2);
                $table->decimal('total_amount', 10, 2)->nullable();
                $table->string('currency', 3)->default('LKR');
                $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
                $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partial'])->default('pending');
                $table->enum('payment_method', ['cash', 'card', 'bank_transfer', 'online'])->nullable();
                $table->string('emergency_contact_name')->nullable();
                $table->string('emergency_contact_phone')->nullable();
                $table->text('special_requirements')->nullable();
                $table->time('arrival_time')->nullable();
                $table->time('departure_time')->nullable();
                $table->enum('booking_source', ['web', 'mobile', 'admin', 'api'])->default('web');
                $table->text('cancellation_reason')->nullable();
                $table->timestamp('cancelled_at')->nullable();
                $table->unsignedBigInteger('cancelled_by')->nullable();
                $table->json('booking_data')->nullable(); // For additional data
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('cancelled_by')->references('id')->on('users')->onDelete('set null');
                
                // Indexes
                $table->index(['user_id', 'booking_status']);
                $table->index(['check_in_date', 'check_out_date']);
                $table->index('payment_status');
            });
        }

        // Create payments table
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_reference')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('booking_id');
                $table->decimal('amount', 10, 2);
                $table->string('currency', 3)->default('LKR');
                $table->enum('payment_method', ['visa', 'mastercard', 'amex', 'bank_transfer', 'cash'])->default('visa');
                $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])->default('pending');
                $table->enum('payment_status', ['pending', 'processing', 'completed', 'succeeded', 'failed', 'cancelled', 'refunded'])->default('pending');
                $table->string('transaction_id')->nullable()->unique();
                // ...existing code...
                $table->string('payment_method_id')->nullable();
                $table->json('gateway_response')->nullable();
                // ...existing code...
                $table->string('authorization_code')->nullable();
                $table->string('card_last_four', 4)->nullable();
                $table->string('card_type')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('failure_reason')->nullable();
                $table->decimal('refund_amount', 10, 2)->nullable();
                $table->timestamp('refunded_at')->nullable();
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
                
                // Indexes
                $table->index(['user_id', 'status']);
                $table->index(['booking_id', 'status']);
                $table->index('transaction_id');
                $table->index('payment_status');
            });
        }

        // Create hostels table (if not exists)
        if (!Schema::hasTable('hostels')) {
            Schema::create('hostels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('location');
                $table->string('address')->nullable();
                $table->string('city');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->enum('type', ['boys', 'girls', 'mixed'])->default('mixed');
                $table->json('amenities')->nullable();
                $table->json('images')->nullable();
                $table->decimal('rating', 3, 2)->default(0);
                $table->integer('total_reviews')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['city', 'type', 'is_active']);
            });
        }

        // Create hostel_packages table (if not exists)
        if (!Schema::hasTable('hostel_packages')) {
            Schema::create('hostel_packages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('hostel_id');
                $table->string('name');
                $table->text('description')->nullable();
                $table->enum('type', ['standard', 'premium', 'deluxe'])->default('standard');
                $table->decimal('monthly_price', 10, 2);
                $table->decimal('daily_price', 10, 2)->nullable();
                $table->integer('capacity')->default(1);
                $table->integer('available_rooms')->default(0);
                $table->json('features')->nullable();
                $table->json('images')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
                $table->index(['hostel_id', 'is_active']);
            });
        }

        // Create cancellation_requests table
        if (!Schema::hasTable('cancellation_requests')) {
            Schema::create('cancellation_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_reference')->unique();
                $table->unsignedBigInteger('booking_id');
                $table->unsignedBigInteger('user_id');
                $table->text('reason');
                $table->enum('status', ['pending', 'approved', 'rejected', 'processed'])->default('pending');
                $table->decimal('refund_amount', 10, 2)->nullable();
                $table->text('admin_notes')->nullable();
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->timestamps();
                
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
                
                $table->index(['booking_id', 'status']);
                $table->index(['user_id', 'status']);
            });
        }

        // Create reviews table (if not exists)
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('hostel_id');
                $table->unsignedBigInteger('booking_id')->nullable();
                $table->integer('rating'); // 1-5
                $table->string('title')->nullable();
                $table->text('comment');
                $table->json('ratings_breakdown')->nullable(); // cleanliness, location, etc.
                $table->boolean('is_verified')->default(false);
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
                
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
                $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
                
                $table->unique(['user_id', 'booking_id']); // One review per booking
                $table->index(['hostel_id', 'is_approved']);
            });
        }

        // Update users table with additional fields (if needed)
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'phone')) {
                    $table->string('phone')->nullable()->after('email');
                }
                if (!Schema::hasColumn('users', 'nic')) {
                    $table->string('nic')->nullable()->after('phone');
                }
                if (!Schema::hasColumn('users', 'address')) {
                    $table->text('address')->nullable()->after('nic');
                }
                if (!Schema::hasColumn('users', 'university')) {
                    $table->string('university')->nullable()->after('address');
                }
                if (!Schema::hasColumn('users', 'faculty')) {
                    $table->string('faculty')->nullable()->after('university');
                }
                if (!Schema::hasColumn('users', 'student_id')) {
                    $table->string('student_id')->nullable()->after('faculty');
                }
                if (!Schema::hasColumn('users', 'year_of_study')) {
                    $table->integer('year_of_study')->nullable()->after('student_id');
                }
                if (!Schema::hasColumn('users', 'emergency_contact_name')) {
                    $table->string('emergency_contact_name')->nullable()->after('year_of_study');
                }
                if (!Schema::hasColumn('users', 'emergency_contact_phone')) {
                    $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
                }
                if (!Schema::hasColumn('users', 'avatar')) {
                    $table->string('avatar')->nullable()->after('emergency_contact_phone');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('avatar');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('cancellation_requests');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('hostel_packages');
        Schema::dropIfExists('hostels');
        
        // Remove added columns from users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $columnsToRemove = [
                    'phone', 'nic', 'address', 'university', 'faculty', 
                    'student_id', 'year_of_study', 'emergency_contact_name', 
                    'emergency_contact_phone', 'avatar', 'is_active'
                ];
                
                foreach ($columnsToRemove as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};