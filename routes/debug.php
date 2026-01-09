<?php
// Create this file as routes/debug.php or add to web.php temporarily

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

// TEMPORARY DEBUG ROUTES - Remove in production
if (app()->environment('local')) {
    
    Route::get('/debug/booking-system', function () {
        $debug = [];
        
        try {
            // 1. Check database connection
            $debug['database_connection'] = 'Working';
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $debug['database_connection'] = 'FAILED: ' . $e->getMessage();
        }
        
        try {
            // 2. Check if tables exist
            $debug['tables'] = [
                'users' => Schema::hasTable('users'),
                'bookings' => Schema::hasTable('bookings'),
                'payments' => Schema::hasTable('payments'),
                'hostels' => Schema::hasTable('hostels'),
            ];
        } catch (\Exception $e) {
            $debug['tables'] = 'FAILED: ' . $e->getMessage();
        }
        
        try {
            // 3. Check BookingController exists
            $debug['booking_controller'] = class_exists('\App\Http\Controllers\BookingController');
        } catch (\Exception $e) {
            $debug['booking_controller'] = 'FAILED: ' . $e->getMessage();
        }
        
        try {
            // 4. Check routes
            $debug['routes'] = [
                'bookings.store' => route('bookings.store'),
                'student.bookings' => route('student.bookings'),
            ];
        } catch (\Exception $e) {
            $debug['routes'] = 'FAILED: ' . $e->getMessage();
        }
        
        try {
            // 5. Check user authentication
            $debug['auth'] = [
                'logged_in' => auth()->check(),
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email ?? 'Not logged in',
            ];
        } catch (\Exception $e) {
            $debug['auth'] = 'FAILED: ' . $e->getMessage();
        }
        
        try {
            // 6. Check Laravel logs for recent errors
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                $logs = file_get_contents($logFile);
                $recentLogs = substr($logs, -2000); // Last 2000 characters
                $debug['recent_logs'] = $recentLogs;
            } else {
                $debug['recent_logs'] = 'Log file does not exist';
            }
        } catch (\Exception $e) {
            $debug['recent_logs'] = 'FAILED: ' . $e->getMessage();
        }
        
        return response()->json($debug, 200, [], JSON_PRETTY_PRINT);
    });
    
    Route::post('/debug/test-booking', function (Request $request) {
        try {
            Log::info('Debug booking test started', $request->all());
            
            // Simulate the booking store process
            $response = [
                'step' => 'Starting debug test',
                'request_data' => $request->except(['card_number', 'card_cvv']),
                'auth_check' => auth()->check(),
                'user_id' => auth()->id(),
            ];
            
            // Test validation
            try {
                $request->validate([
                    'hostel_id' => 'required|integer',
                    'emergency_contact_name' => 'required|string',
                ]);
                $response['validation'] = 'PASSED';
            } catch (\Exception $e) {
                $response['validation'] = 'FAILED: ' . $e->getMessage();
                return response()->json($response, 422);
            }
            
            // Test database operations
            try {
                if (Schema::hasTable('bookings')) {
                    $response['database'] = 'Bookings table exists';
                } else {
                    $response['database'] = 'Bookings table missing';
                }
            } catch (\Exception $e) {
                $response['database'] = 'FAILED: ' . $e->getMessage();
            }
            
            $response['status'] = 'Debug test completed successfully';
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            Log::error('Debug booking test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'FAILED',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    });
    
    Route::get('/debug/create-tables', function () {
        try {
            $results = [];
            
            // Create bookings table if it doesn't exist
            if (!Schema::hasTable('bookings')) {
                Schema::create('bookings', function ($table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained()->onDelete('cascade');
                    $table->unsignedBigInteger('hostel_id');
                    $table->string('booking_reference')->unique();
                    $table->string('package')->nullable();
                    $table->string('duration')->nullable();
                    $table->integer('package_id')->nullable();
                    $table->date('check_in_date');
                    $table->date('check_out_date');
                    $table->integer('duration_days')->nullable();
                    $table->decimal('total_amount', 10, 2);
                    $table->decimal('amount', 10, 2)->nullable();
                    $table->string('status')->default('pending');
                    $table->string('booking_status')->default('pending');
                    $table->string('payment_status')->default('pending');
                    $table->string('payment_method');
                    $table->string('emergency_contact_name');
                    $table->string('emergency_contact_phone');
                    $table->text('special_requests')->nullable();
                    $table->string('student_id')->nullable();
                    $table->string('university')->nullable();
                    $table->text('dietary_requirements')->nullable();
                    $table->timestamp('booked_at')->nullable();
                    $table->timestamps();
                });
                $results['bookings'] = 'Created successfully';
            } else {
                $results['bookings'] = 'Already exists';
            }
            
            // Create hostels table if it doesn't exist
            if (!Schema::hasTable('hostels')) {
                Schema::create('hostels', function ($table) {
                    $table->id();
                    $table->string('name');
                    $table->string('location');
                    $table->text('description')->nullable();
                    $table->decimal('price_per_month', 10, 2)->default(18000);
                    $table->boolean('is_active')->default(true);
                    $table->timestamps();
                });
                
                // Insert sample data
                DB::table('hostels')->insert([
                    'id' => 3,
                    'name' => 'SLIIT Boys Hostel',
                    'location' => 'Malabe',
                    'description' => 'Comfortable hostel for students',
                    'price_per_month' => 18000,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $results['hostels'] = 'Created successfully with sample data';
            } else {
                $results['hostels'] = 'Already exists';
            }
            
            // Create payments table if it doesn't exist  
            if (!Schema::hasTable('payments')) {
                Schema::create('payments', function ($table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained();
                    $table->foreignId('booking_id')->constrained();
                    $table->decimal('amount', 10, 2);
                    $table->string('payment_method');
                    $table->string('card_last_four', 4)->nullable();
                    $table->string('transaction_id')->nullable();
                    $table->string('status')->default('pending');
                    $table->timestamps();
                });
                $results['payments'] = 'Created successfully';
            } else {
                $results['payments'] = 'Already exists';
            }
            
            return response()->json([
                'message' => 'Tables creation completed',
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create tables',
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    });
}