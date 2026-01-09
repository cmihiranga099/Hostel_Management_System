<?php

// Migration 1: create_booking_update_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingUpdateHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('booking_update_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('update_reason');
            $table->text('other_reason')->nullable();
            $table->json('original_data');
            $table->json('updated_data');
            $table->decimal('price_difference', 10, 2)->default(0);
            $table->timestamp('created_at');
            
            $table->index(['booking_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_update_histories');
    }
}

// Migration 2: create_cancellation_requests_table.php

class CreateCancellationRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reason');
            $table->text('details')->nullable();
            $table->decimal('original_amount', 10, 2);
            $table->decimal('refund_amount', 10, 2);
            $table->decimal('final_refund_amount', 10, 2)->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'requested_at']);
            $table->index('booking_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cancellation_requests');
    }
}

// Migration 3: create_refunds_table.php

class CreateRefundsTable extends Migration
{
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['processing', 'completed', 'failed', 'cancelled'])->default('processing');
            $table->timestamp('processed_at');
            $table->string('payment_method')->default('card');
            $table->string('reference_number')->unique();
            $table->json('gateway_response')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'processed_at']);
            $table->index('reference_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}

// Migration 4: add_status_columns_to_bookings_table.php

class AddStatusColumnsToBookingsTable extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Add new status options if not already present
            $table->string('status')->default('pending')->change();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('updated_at')->nullable()->change();
        });
        
        // Update the status enum to include new values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'cancellation_requested', 'completed') DEFAULT 'pending'");
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['cancelled_at']);
        });
        
        // Revert status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending'");
    }
}

// Migration 5: create_packages_table.php (if not exists)

class CreatePackagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hostel_id')->constrained()->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('monthly_price', 10, 2);
                $table->decimal('daily_price', 8, 2)->nullable();
                $table->json('amenities')->nullable();
                $table->integer('max_occupancy')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['hostel_id', 'is_active']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}