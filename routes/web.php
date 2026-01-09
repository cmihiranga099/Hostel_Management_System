<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\StudentHostelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\DummyPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ==========================================
// PUBLIC ROUTES (No Authentication Required)
// ==========================================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Us
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Contact Us  
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Contact form submission alias (for backward compatibility)
Route::post('/contact/submit', [ContactController::class, 'store'])->name('contact.submit');

// Public Hostel Listing (Browse without booking)
Route::get('/hostels', [HostelController::class, 'index'])->name('hostels');
Route::get('/hostels/{id}', [HostelController::class, 'show'])->name('hostels.show');

// Backward compatibility routes
Route::get('/hostel/{id}', [HostelController::class, 'show'])->name('hostel.details');

// Public Reviews (Read Only)
Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/reviews/{hostel}', [ReviewController::class, 'hostelReviews'])->name('reviews.hostel');

// Route aliases for backward compatibility
Route::redirect('/hostels-list', '/hostels')->name('hostels.index');

// Search and Filter Routes
Route::get('/search', [HostelController::class, 'search'])->name('hostels.search');
Route::get('/filter', [HostelController::class, 'filter'])->name('hostels.filter');

// City-based browsing
Route::get('/hostels/city/{city}', [HostelController::class, 'byCity'])->name('hostels.city');
Route::get('/hostels/type/{type}', [HostelController::class, 'byType'])->name('hostels.type');

// Test Route (for development)
Route::get('/test', function () {
    return 'Laravel is working!';
});

// ==========================================
// DEBUG ROUTES (for testing - remove in production)
// ==========================================
if (app()->environment('local')) {
    // Test route to see if basic routing works
    Route::get('/test-book/{id}', function($id) {
        return "Test booking for hostel ID: " . $id;
    })->name('test.book');

    // Debug route to see what's happening
    Route::get('/debug-hostel/{id}', [HostelController::class, 'debug'])->name('debug.hostel');
    
    // Test direct controller access
    Route::get('/test-controller/{id}', function($id) {
        $controller = new \App\Http\Controllers\HostelController();
        return $controller->getHostelData($id);
    })->name('test.controller');
    
    // ENHANCED DEBUG ROUTES FOR PAYMENT TESTING
    Route::get('/test-connection', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'Laravel is working',
            'timestamp' => now(),
            'environment' => app()->environment(),
            'authenticated' => auth()->check(),
            'user_id' => auth()->id()
        ]);
    })->name('test.connection');

    // Debug payment routes
    Route::get('/debug-payment-routes', function () {
        $paymentRoutes = [];
        foreach (Route::getRoutes() as $route) {
            if (str_contains($route->uri(), 'payment') || str_contains($route->getName() ?? '', 'payment')) {
                $paymentRoutes[] = [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName()
                ];
            }
        }
        return response()->json($paymentRoutes);
    })->name('debug.payment.routes');

    // Test payment processing without form
    Route::get('/test-payment-process', function () {
        // Create a test request
        $request = new \Illuminate\Http\Request();
        $request->merge([
            'booking_id' => 1,
            'payment_method' => 'visa',
            'card_number' => '4111111111111111',
            'card_name' => 'Test User',
            'card_expiry_month' => '12',
            'card_expiry_year' => '2025',
            'card_cvv' => '123',
            'amount' => 19000
        ]);
        
        $controller = new \App\Http\Controllers\PaymentController();
        
        try {
            $response = $controller->processPayment($request);
            return response()->json([
                'success' => true,
                'message' => 'Payment test completed successfully',
                'response' => $response->getData()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment test failed: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    })->name('test.payment.process');

    // Check authentication status
    Route::get('/debug-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'user' => auth()->user(),
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token()
        ]);
    })->name('debug.auth');

    // Test database connection
    Route::get('/test-database', function () {
        try {
            \DB::connection()->getPdo();
            
            $tables = [];
            $tableNames = ['users', 'bookings', 'payments', 'hostels', 'hostel_packages'];
            
            foreach ($tableNames as $tableName) {
                $exists = \Schema::hasTable($tableName);
                $count = $exists ? \DB::table($tableName)->count() : 0;
                $tables[$tableName] = [
                    'exists' => $exists,
                    'count' => $count
                ];
            }
            
            return response()->json([
                'database_connected' => true,
                'tables' => $tables
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'database_connected' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('test.database');

    // Force create sample booking
    Route::get('/create-sample-booking', function () {
        try {
            if (!auth()->check()) {
                return response()->json([
                    'error' => 'Not authenticated. Please login first.'
                ], 401);
            }

            $bookingData = [
                'booking_reference' => 'BK-TEST-' . uniqid(),
                'user_id' => auth()->id(),
                'hostel_id' => 1,
                'hostel_package_id' => 1,
                'check_in_date' => now()->addDays(10),
                'check_out_date' => now()->addDays(40),
                'duration' => 30,
                'amount' => 19000,
                'total_amount' => 19000,
                'booking_status' => 'pending',
                'payment_status' => 'pending'
            ];

            if (class_exists('\App\Models\Booking')) {
                $booking = \App\Models\Booking::create($bookingData);
                return response()->json([
                    'success' => true,
                    'booking' => $booking,
                    'payment_url' => route('payments.show', $booking->id)
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'booking' => $bookingData,
                    'message' => 'Sample booking data created (no database model)',
                    'payment_url' => route('payments.show', 1)
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    })->name('create.sample.booking');

    // Get CSRF token
    Route::get('/get-csrf-token', function () {
        return response()->json([
            'csrf_token' => csrf_token(),
            'session_token' => session()->token()
        ]);
    })->name('get.csrf.token');

    // Clear all caches
    Route::get('/clear-all-cache', function () {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'All caches cleared successfully',
                'commands_run' => ['cache:clear', 'config:clear', 'view:clear', 'route:clear']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('debug.clear.cache');

    // Log viewer (simple)
    Route::get('/view-logs', function () {
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $logs = file_get_contents($logFile);
            $recentLogs = substr($logs, -10000); // Last 10000 characters
            return response('<pre>' . htmlspecialchars($recentLogs) . '</pre>');
        }
        return 'No log file found';
    })->name('view.logs');

    // Generate test payment form data
    Route::get('/generate-test-payment', function () {
        return response()->json([
            'form_data' => [
                'booking_id' => 1,
                'payment_method' => 'visa',
                'card_number' => '4111 1111 1111 1111',
                'card_name' => 'John Doe',
                'card_expiry_month' => '12',
                'card_expiry_year' => '2025', 
                'card_cvv' => '123',
                'amount' => 19000,
                'cardholder_name' => 'John Doe',
                'email' => 'john@example.com'
            ],
            'test_cards' => [
                'success' => '4111111111111111',
                'declined' => '4000000000000002',
                'insufficient_funds' => '4000000000000003',
                'expired' => '4000000000000004'
            ]
        ]);
    })->name('generate.test.payment');
}

// ==========================================
// AUTHENTICATION ROUTES
// ==========================================

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    // Password Confirmation
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Authentication Routes (Laravel Breeze/UI)
require __DIR__.'/auth.php';

// ==========================================
// AUTHENTICATED USER ROUTES
// ==========================================

Route::middleware(['auth'])->group(function () {
    
    // ==========================================
    // PROFILE ROUTES (Global - Not Student Specific)
    // ==========================================
    
    // These routes are available to all authenticated users (students, admins, etc.)
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    
    // ==========================================
    // FIXED STUDENT PROFILE ROUTES WITH DATABASE OPERATIONS
    // ==========================================
    
    // Profile Edit Form - Fixed to work with database
    Route::get('/student/profile/edit', function() {
        try {
            $user = Auth::user();
            return view('student.profile.edit', compact('user'));
            
        } catch (\Exception $e) {
            \Log::error('Profile edit error: ' . $e->getMessage());
            return redirect()->route('student.dashboard')
                ->with('error', 'Unable to load profile edit form.');
        }
    })->name('student.profile.edit');
    
    // Profile Update - Fixed to save to database
    Route::patch('/student/profile', function() {
        try {
            $user = Auth::user();
            
            // Validate the input data
            $validatedData = request()->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string|max:20',
                'nic' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
                'university' => 'nullable|string|max:255',
                'faculty' => 'nullable|string|max:255',
                'student_id' => 'nullable|string|max:50',
                'year_of_study' => 'nullable|integer|min:1|max:6',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            // Handle avatar upload
            if (request()->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                    \Storage::disk('public')->delete($user->avatar);
                }
                
                // Store new avatar
                $avatarPath = request()->file('avatar')->store('avatars', 'public');
                $validatedData['avatar'] = $avatarPath;
            }
            
            // Update user in database
            $user->update($validatedData);
            
            \Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'updated_fields' => array_keys($validatedData)
            ]);
            
            return redirect()->route('student.profile.edit')
                ->with('success', 'Profile updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->with('error', 'Profile update failed: ' . $e->getMessage())
                ->withInput();
        }
    })->name('student.profile.update');
    
    // Password Update - Fixed to save to database
    Route::patch('/student/profile/password', function() {
        try {
            $user = Auth::user();
            
            // Validate password data
            $validatedData = request()->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            // Check if current password is correct
            if (!Hash::check($validatedData['current_password'], $user->password)) {
                return redirect()->back()
                    ->with('error', 'Current password is incorrect.');
            }
            
            // Update password in database
            $user->update([
                'password' => Hash::make($validatedData['password'])
            ]);
            
            \Log::info('Password updated successfully', [
                'user_id' => $user->id
            ]);
            
            return redirect()->route('student.profile.edit')
                ->with('success', 'Password updated successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator);
                
        } catch (\Exception $e) {
            \Log::error('Password update error: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Password update failed: ' . $e->getMessage());
        }
    })->name('student.profile.password');
    
    // Account Deletion - Fixed to properly delete from database
    Route::delete('/student/profile', function() {
        try {
            $user = Auth::user();
            
            // Validate password
            $validatedData = request()->validate([
                'password' => 'required|string'
            ]);
            
            // Check if password is correct
            if (!Hash::check($validatedData['password'], $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password is incorrect.');
            }
            
            // Delete user avatar if exists
            if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                \Storage::disk('public')->delete($user->avatar);
            }
            
            // Log the deletion
            \Log::info('User account deleted', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);
            
            // Logout and delete user
            Auth::logout();
            $user->delete();
            
            return redirect()->route('home')
                ->with('success', 'Account deleted successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Account deletion error: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            
            return redirect()->back()
                ->with('error', 'Account deletion failed: ' . $e->getMessage());
        }
    })->name('student.profile.destroy');
    
    // Avatar Update via AJAX - Fixed to save to database
    Route::post('/student/profile/avatar', function() {
        try {
            $user = Auth::user();
            
            // Validate avatar
            request()->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            if (request()->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
                    \Storage::disk('public')->delete($user->avatar);
                }
                
                // Store new avatar
                $avatarPath = request()->file('avatar')->store('avatars', 'public');
                
                // Update user in database
                $user->update(['avatar' => $avatarPath]);
                
                \Log::info('Avatar updated successfully', [
                    'user_id' => $user->id,
                    'avatar_path' => $avatarPath
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Avatar updated successfully!',
                    'avatar_url' => asset('storage/' . $avatarPath)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No avatar file provided'
            ], 400);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            \Log::error('Avatar update error: ' . $e->getMessage(), [
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Avatar update failed: ' . $e->getMessage()
            ], 500);
        }
    })->name('student.profile.avatar');
    
    // ==========================================
    // DASHBOARD ROUTING (Updated Structure)
    // ==========================================
    
    // Main Dashboard (redirects to appropriate dashboard)
   
     Route::get('/dashboard', function () {
        // For now, just redirect to student dashboard since we don't have roles implemented yet
        // TODO: Implement role-based redirecting when user roles are added to database
        // if (auth()->user()->hasRole('admin')) {
        //     return redirect()->route('admin.dashboard');
        // }
        return redirect()->route('student.dashboard');
    })->name('dashboard');
    // ==========================================
    // BOOKING ROUTES (ENHANCED WITH UPDATE & DELETE)
    // ==========================================
    
    // CRITICAL FIX: ADD BOOKING CREATION ROUTES FIRST
    // These routes handle the actual booking form submission BEFORE payment
    
    // Show booking form (this is where you currently are)
    Route::get('/hostels/{id}/book', [HostelController::class, 'book'])->name('hostels.book');
    Route::get('/book/{id}', [HostelController::class, 'book'])->name('book.hostel');
    Route::get('/booking/{id}', [HostelController::class, 'book'])->name('booking.hostel');
    
    // FIXED: Add the missing createBooking route that was causing the error
    Route::post('/hostels/{id}/create-booking', [HostelController::class, 'createBooking'])->name('hostels.createBooking');
    
    // Process booking submission (creates booking, then redirects to payment)
    Route::post('/hostels/{id}/book', [HostelController::class, 'processBooking'])->name('hostels.process-booking');
    Route::post('/book/{id}', [HostelController::class, 'processBooking'])->name('process.booking');
    
    // Alternative booking processing routes
    Route::post('/bookings/create', [BookingController::class, 'store'])->name('bookings.create');
    Route::post('/bookings/submit', [BookingController::class, 'store'])->name('bookings.submit');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    
    // EXISTING BOOKING MANAGEMENT ROUTES
    Route::get('/booking/create/{id}', [BookingController::class, 'create'])->name('booking.create');
    
    // Booking form submission (ENHANCED WITH AJAX SUPPORT)
    Route::post('/hostels/{id}/book-old', [HostelController::class, 'createBooking'])->name('hostels.store-booking');
    
    // NEW: AJAX Booking submission routes
    Route::post('/bookings/ajax-store', [BookingController::class, 'ajaxStore'])->name('bookings.ajax-store');
    
    // Booking availability check
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
    
    // Booking management routes (ENHANCED)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
    Route::patch('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.patch');
    Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/{id}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
    Route::post('/bookings/{id}/extend', [BookingController::class, 'extend'])->name('bookings.extend');
    
    // NEW: Booking success page
    Route::get('/bookings/{id}/success', [BookingController::class, 'showSuccess'])->name('bookings.success');
    
    // NEW: Cancellation Request Routes
    Route::post('/bookings/{id}/cancel-request', [BookingController::class, 'cancelRequest'])->name('bookings.cancel-request');
    Route::get('/bookings/{id}/history', [BookingController::class, 'history'])->name('bookings.history');
    
    // Quick booking
    Route::post('/quick-book', [BookingController::class, 'quickBook'])->name('bookings.quick');
    
    // ==========================================
    // ENHANCED PAYMENT ROUTES (FIXED FOR HTTP_ERROR_404)
    // ==========================================
    
    Route::prefix('payments')->name('payments.')->group(function () {
        // Show payment page
        Route::get('/{booking}', [PaymentController::class, 'showPaymentPage'])->name('show');
        
        // MAIN PAYMENT PROCESSING ROUTES (Multiple endpoints for compatibility)
        Route::post('/process', [PaymentController::class, 'processPayment'])->name('process');
        Route::post('/submit', [PaymentController::class, 'processPayment'])->name('submit');
        Route::post('/charge', [PaymentController::class, 'processPayment'])->name('charge');
        Route::post('/pay', [PaymentController::class, 'processPayment'])->name('pay');
        
        // Success/Failure pages
        Route::get('/success/{paymentId}', [PaymentController::class, 'paymentSuccess'])->name('success');
        Route::get('/failed/{paymentId}', [PaymentController::class, 'paymentFailed'])->name('failed');
        Route::get('/cancelled/{paymentId}', [PaymentController::class, 'paymentCancelled'])->name('cancelled');
        
        // Payment history (accessible from main nav)
        Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('history');
        
        // Payment details and management
        Route::get('/details/{paymentId}', [PaymentController::class, 'paymentDetails'])->name('details');
        
        // Refund requests
        Route::post('/refund/{paymentId}', [PaymentController::class, 'requestRefund'])->name('refund');
        Route::get('/refund-status/{paymentId}', [PaymentController::class, 'refundStatus'])->name('refund.status');
    });
    
    // ==========================================
    // ADDITIONAL PAYMENT ROUTES FOR COMPATIBILITY (NEW)
    // ==========================================
    
    // Direct payment processing routes (outside of prefix group for broader compatibility)
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::post('/payment/submit', [PaymentController::class, 'processPayment'])->name('payment.submit');
    Route::post('/payment/charge', [PaymentController::class, 'processPayment'])->name('payment.charge');
    Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('process.payment');
    Route::post('/submit-payment', [PaymentController::class, 'processPayment'])->name('submit.payment');
    
    // Booking-specific payment routes
    Route::post('/bookings/{id}/pay', [PaymentController::class, 'processPayment'])->name('bookings.pay');
    Route::post('/hostels/{id}/pay', [PaymentController::class, 'processPayment'])->name('hostels.pay');
    
    // Generic fallback payment routes
    Route::post('/pay', [PaymentController::class, 'processPayment'])->name('pay');
    Route::post('/charge', [PaymentController::class, 'processPayment'])->name('charge');
    
    // ==========================================
    // STUDENT DASHBOARD ROUTES (Enhanced Structure)
    // ==========================================
    
    Route::prefix('student')->name('student.')->group(function () {
        // Main Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/stats', [StudentDashboardController::class, 'getStats'])->name('dashboard.stats');
        Route::post('/dashboard/quick-search', [StudentDashboardController::class, 'quickSearch'])->name('dashboard.quick-search');
        Route::get('/dashboard/notifications', [StudentDashboardController::class, 'getNotifications'])->name('dashboard.notifications');
        
        // Dashboard Pages
        Route::get('/profile', [StudentDashboardController::class, 'profile'])->name('profile');
        Route::get('/bookings', [StudentDashboardController::class, 'bookings'])->name('bookings');
        Route::get('/payments', [StudentDashboardController::class, 'payments'])->name('payments');
        Route::get('/reviews', [StudentDashboardController::class, 'reviews'])->name('reviews');
        
        // Student Hostel Routes (for booking) - Fixed to use correct controller methods
        Route::prefix('hostels')->name('hostels.')->group(function () {
            Route::get('/', [StudentHostelController::class, 'listHostels'])->name('index');
            Route::get('/{id}', [StudentHostelController::class, 'show'])->name('show');
            Route::get('/{id}/book', [StudentHostelController::class, 'book'])->name('book');
            Route::post('/{id}/book', [StudentHostelController::class, 'storeBooking'])->name('store-booking');
            Route::get('/{id}/packages', [StudentHostelController::class, 'getPackages'])->name('packages');
            Route::post('/{id}/check-availability', [StudentHostelController::class, 'checkAvailability'])->name('check-availability');
        });
        
        // Booking Management (CRUD) - Student context routes (ENHANCED)
        Route::get('/bookings/{id}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::patch('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.patch');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('/bookings/{id}/invoice', [BookingController::class, 'invoice'])->name('bookings.invoice');
        Route::post('/bookings/{id}/extend', [BookingController::class, 'extend'])->name('bookings.extend');
        
        // NEW: Student Cancellation Request Routes
        Route::post('/bookings/{id}/cancel-request', [BookingController::class, 'cancelRequest'])->name('bookings.cancel-request');
        Route::get('/bookings/{id}/history', [BookingController::class, 'history'])->name('bookings.history');
        
        // Payment History - Original routes
        Route::get('/payments/{id}', [PaymentController::class, 'paymentDetails'])->name('payments.show');
        Route::post('/payments/{id}/refund', [PaymentController::class, 'requestRefund'])->name('payments.refund');
        
        // Reviews (CRUD for own reviews) - Original routes (using student prefix)
        Route::get('/reviews/create/{booking?}', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::patch('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        
        // Notifications
        Route::get('/notifications', [StudentDashboardController::class, 'notifications'])->name('notifications');
        Route::patch('/notifications/{id}/read', [StudentDashboardController::class, 'markAsRead'])->name('notifications.read');
        Route::delete('/notifications/{id}', [StudentDashboardController::class, 'deleteNotification'])->name('notifications.delete');
        
        // Settings
        Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
        Route::patch('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
    });
    
    // ==========================================
    // ADMIN ROUTES (NEW - For Managing Cancellation Requests)
    // ==========================================
    
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Cancellation Request Management
        Route::prefix('cancellations')->name('cancellations.')->group(function () {
            Route::get('/', [BookingController::class, 'cancellationRequests'])->name('index');
            Route::get('/{cancellationRequest}', [BookingController::class, 'showCancellationRequest'])->name('show');
            Route::put('/{cancellationRequest}/process', [BookingController::class, 'processCancellationRequest'])->name('process');
            Route::get('/export', [BookingController::class, 'exportCancellationRequests'])->name('export');
        });
        
        // Admin Booking Management
        Route::prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', [BookingController::class, 'adminIndex'])->name('index');
            Route::get('/{booking}', [BookingController::class, 'adminShow'])->name('show');
            Route::put('/{booking}/status', [BookingController::class, 'updateStatus'])->name('update-status');
            Route::delete('/{booking}', [BookingController::class, 'destroy'])->name('destroy');
            
            // Bulk operations
            Route::post('/bulk-action', [BookingController::class, 'bulkAction'])->name('bulk-action');
        });
        
        // Refund Management
        Route::prefix('refunds')->name('refunds.')->group(function () {
            Route::get('/', [PaymentController::class, 'adminRefunds'])->name('index');
            Route::get('/{refund}', [PaymentController::class, 'showRefund'])->name('show');
            Route::put('/{refund}/process', [PaymentController::class, 'processRefund'])->name('process');
        });
    });
    
    // ==========================================
    // BACKWARD COMPATIBILITY ROUTES
    // ==========================================
    
    Route::get('/dashboard/profile', function() {
        return redirect()->route('student.profile');
    })->name('dashboard.profile');
    
    Route::get('/dashboard/bookings', function() {
        return redirect()->route('student.bookings');
    })->name('dashboard.bookings');
    
    Route::get('/dashboard/reviews', function() {
        return redirect()->route('student.reviews');
    })->name('dashboard.reviews');
    
    Route::put('/dashboard/profile', function() {
        return redirect()->route('student.profile');
    })->name('dashboard.profile.update');
    
    // Original StudentDashboard routes for backward compatibility
    Route::get('/student-dashboard-old', [StudentDashboardController::class, 'index'])->name('student.dashboard.old');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my.bookings');
    Route::get('/payment-history', [PaymentController::class, 'paymentHistory'])->name('payment.history');
    Route::get('/my-reviews', [ReviewController::class, 'myReviews'])->name('my.reviews');
    
    // ==========================================
    // REVIEW ROUTES (Public Read + Auth Write)
    // ==========================================
    
    // Anyone can write reviews after booking
    Route::get('/reviews/create/{booking?}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::patch('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Support and Help
    Route::get('/help', [HomeController::class, 'help'])->name('help');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::post('/support-ticket', [ContactController::class, 'supportTicket'])->name('support.ticket');
});

// ==========================================
// API ROUTES FOR AJAX CALLS (ENHANCED)
// ==========================================

Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Dashboard Stats (for real-time updates)
    Route::get('/dashboard-stats', [StudentDashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/notifications', [StudentDashboardController::class, 'getNotifications'])->name('notifications');
    
    // Quick Actions
    Route::post('/quick-book', [BookingController::class, 'quickBook'])->name('quick-book');
    Route::get('/search-hostels', [HostelController::class, 'apiSearch'])->name('search-hostels');
    Route::get('/check-availability', [BookingController::class, 'apiCheckAvailability'])->name('check-availability');
    
    // ENHANCED: Payment processing API routes (Multiple endpoints for compatibility)
    Route::post('/payments/process', [PaymentController::class, 'processPayment'])->name('payments.process');
    Route::post('/payments/submit', [PaymentController::class, 'processPayment'])->name('payments.submit');
    Route::post('/payments/charge', [PaymentController::class, 'processPayment'])->name('payments.charge');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::post('/payment/submit', [PaymentController::class, 'processPayment'])->name('payment.submit');
    Route::post('/payment/charge', [PaymentController::class, 'processPayment'])->name('payment.charge');
    
    // Alternative booking submission for better compatibility
    Route::post('/bookings/create', [BookingController::class, 'store'])->name('bookings.create');
    
    // Booking price and refund calculations
    Route::post('/bookings/calculate-price', [BookingController::class, 'calculatePrice'])->name('bookings.calculate-price');
    Route::get('/bookings/{booking}/calculate-refund', [BookingController::class, 'calculateRefund'])->name('bookings.calculate-refund');
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
    Route::get('/bookings/{booking}/update-history', [BookingController::class, 'getUpdateHistory'])->name('bookings.update-history');
    
    // Profile completion
    Route::get('/profile/completion', [ProfileController::class, 'getCompletionStatus'])->name('profile.completion');
    Route::patch('/profile/quick-update', [ProfileController::class, 'quickUpdate'])->name('profile.quick-update');
    
    // Real-time booking updates
    Route::patch('/bookings/{id}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    
    // Test connection for API
    Route::get('/test-connection', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API connection working',
            'timestamp' => now(),
            'authenticated' => auth()->check(),
            'user_id' => auth()->id()
        ]);
    })->name('test.api.connection');
});

// ==========================================
// WEBHOOK ROUTES (No Authentication)
// ==========================================

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    // Payment Gateway Webhooks
    // ...existing code...
    Route::post('/paypal', [PaymentController::class, 'paypalWebhook'])->name('paypal');
    Route::post('/razorpay', [PaymentController::class, 'razorpayWebhook'])->name('razorpay');
    
    // Email Service Webhooks
    Route::post('/email/delivered', [HomeController::class, 'emailDelivered'])->name('email.delivered');
    Route::post('/email/bounced', [HomeController::class, 'emailBounced'])->name('email.bounced');
});

// ==========================================
// SITEMAP AND SEO ROUTES
// ==========================================

Route::get('/sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [HomeController::class, 'robots'])->name('robots');

// ==========================================
// ERROR AND UTILITY ROUTES
// ==========================================

// Health Check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'environment' => app()->environment(),
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION
    ]);
})->name('health');

// Maintenance Mode
Route::get('/maintenance', function () {
    return view('errors.maintenance');
})->name('maintenance');

// Privacy Policy and Terms
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy');
Route::get('/terms-of-service', [HomeController::class, 'termsOfService'])->name('terms');
Route::get('/cookie-policy', [HomeController::class, 'cookiePolicy'])->name('cookies');

// ==========================================
// DEVELOPMENT AND TESTING ROUTES (ENHANCED)
// ==========================================

if (app()->environment('local')) {
    
    // Test middleware
    Route::middleware(['auth'])->get('/test-auth-middleware', function () {
        return response()->json([
            'success' => true,
            'message' => 'Auth middleware is working',
            'user' => auth()->user(),
            'permissions' => []
        ]);
    })->name('test.auth.middleware');
    
    // Test form submission
    Route::post('/test-form-submission', function () {
        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully',
            'data' => request()->all(),
            'method' => request()->method(),
            'headers' => request()->headers->all()
        ]);
    })->name('test.form.submission');
    
    // Generate sample data
    Route::get('/generate-sample-data', function () {
        try {
            $sampleData = [
                'hostel' => [
                    'id' => 1,
                    'name' => 'Mixed University Hostel - Kandy',
                    'location' => 'Kandy',
                    'type' => 'mixed',
                    'monthly_rate' => 18000
                ],
                'booking' => [
                    'id' => 1,
                    'booking_reference' => 'BK-DEMO001',
                    'user_id' => auth()->id(),
                    'hostel_id' => 1,
                    'check_in_date' => now()->addDays(10)->format('Y-m-d'),
                    'check_out_date' => now()->addDays(40)->format('Y-m-d'),
                    'amount' => 19000,
                    'status' => 'pending'
                ],
                'payment' => [
                    'id' => 1,
                    'payment_reference' => 'PAY-DEMO001',
                    'booking_id' => 1,
                    'amount' => 19000,
                    'status' => 'pending'
                ]
            ];
            
            return response()->json([
                'success' => true,
                'sample_data' => $sampleData,
                'urls' => [
                    'payment_page' => route('payments.show', 1),
                    'payment_process' => route('payments.process'),
                    'payment_success' => route('payments.success', 1)
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    })->name('generate.sample.data');
    
    // Route list with filtering
    Route::get('/debug-routes/{filter?}', function ($filter = null) {
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $routeData = [
                'method' => implode('|', $route->methods()),
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'middleware' => $route->gatherMiddleware()
            ];
            
            if ($filter) {
                if (str_contains($route->uri(), $filter) || 
                    str_contains($route->getName() ?? '', $filter) ||
                    str_contains($route->getActionName(), $filter)) {
                    $routes[] = $routeData;
                }
            } else {
                $routes[] = $routeData;
            }
        }
        
        return response()->json([
            'total_routes' => count($routes),
            'filter' => $filter,
            'routes' => $routes
        ]);
    })->name('debug.routes');
    
    // Test email templates
    Route::get('/test-email/{template}', function ($template) {
        try {
            return view("emails.{$template}");
        } catch (\Exception $e) {
            return response("Email template '{$template}' not found: " . $e->getMessage(), 404);
        }
    })->name('test.email');
    
    // Test notifications
    Route::get('/test-notification', function () {
        try {
            if (class_exists('\App\Notifications\BookingConfirmed') && class_exists('\App\Models\Booking')) {
                $booking = \App\Models\Booking::first();
                if ($booking) {
                    auth()->user()->notify(new \App\Notifications\BookingConfirmed($booking));
                    return 'Notification sent successfully!';
                } else {
                    return 'No bookings found to send notification';
                }
            }
            return 'Notification classes not found';
        } catch (\Exception $e) {
            return 'Notification test failed: ' . $e->getMessage();
        }
    })->name('test.notification');
    
    // Performance test
    Route::get('/test-performance', function () {
        $start = microtime(true);
        
        // Simulate some work
        sleep(1);
        
        $end = microtime(true);
        $executionTime = $end - $start;
        
        return response()->json([
            'execution_time' => $executionTime,
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
            'timestamp' => now()
        ]);
    })->name('test.performance');
    
    // Error simulation
    Route::get('/test-error/{type?}', function ($type = '500') {
        switch ($type) {
            case '404':
                abort(404);
            case '403':
                abort(403);
            case '422':
                abort(422, 'Validation Error');
            case 'exception':
                throw new \Exception('Test exception');
            default:
                abort(500);
        }
    })->name('test.error');
}

// ==========================================
// FALLBACK ROUTE (Must be last)
// ==========================================

// 404 Handler
Route::fallback(function () {
    if (request()->wantsJson()) {
        return response()->json([
            'error' => 'Route not found',
            'message' => 'The requested route does not exist',
            'url' => request()->url(),
            'method' => request()->method()
        ], 404);
    }
    
    return response()->view('errors.404', [
        'url' => request()->url(),
        'method' => request()->method()
    ], 404);
});

// ==========================================
// ROUTE MODEL BINDINGS (IMPROVED)
// ==========================================

Route::bind('hostel', function ($value) {
    if (class_exists('\App\Models\Hostel')) {
        try {
            return \App\Models\Hostel::where('id', $value)->orWhere('slug', $value)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Hostel not found');
        }
    }
    
    // Return sample data if model doesn't exist
    return (object) [
        'id' => $value,
        'name' => 'Mixed University Hostel - Kandy',
        'location' => 'Kandy',
        'type' => 'mixed',
        'description' => 'A modern mixed hostel facility in Kandy',
        'monthly_rate' => 18000
    ];
});

Route::bind('booking', function ($value) {
    if (class_exists('\App\Models\Booking')) {
        try {
            return \App\Models\Booking::where('id', $value)->orWhere('booking_reference', $value)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Booking not found');
        }
    }
    
    // Return sample data if model doesn't exist
    return (object) [
        'id' => $value,
        'booking_reference' => 'BK-DEMO' . str_pad($value, 3, '0', STR_PAD_LEFT),
        'user_id' => auth()->id(),
        'hostel_id' => 1,
        'amount' => 19000,
        'status' => 'pending',
        'payment_status' => 'pending'
    ];
});

Route::bind('payment', function ($value) {
    if (class_exists('\App\Models\Payment')) {
        try {
            return \App\Models\Payment::where('id', $value)->orWhere('payment_reference', $value)->firstOrFail();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Payment not found');
        }
    }
    
    // Return sample data if model doesn't exist
    return (object) [
        'id' => $value,
        'payment_reference' => 'PAY-DEMO' . str_pad($value, 3, '0', STR_PAD_LEFT),
        'booking_id' => 1,
        'user_id' => auth()->id(),
        'amount' => 19000,
        'status' => 'completed',
        'payment_method' => 'visa'
    ];
});

// Route binding for cancellation requests
Route::bind('cancellationRequest', function ($value) {
    if (class_exists('\App\Models\CancellationRequest')) {
        try {
            return \App\Models\CancellationRequest::findOrFail($value);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Cancellation request not found');
        }
    }
    
    // Return sample data if model doesn't exist
    return (object) [
        'id' => $value,
        'booking_id' => 1,
        'user_id' => auth()->id(),
        'status' => 'pending',
        'reason' => 'Sample cancellation request'
    ];
});

// Dummy Payment Gateway routes
Route::get('/payments/dummy/{booking}', [DummyPaymentController::class, 'showForm'])->name('payments.dummy.form');
Route::post('/payments/dummy/{booking}', [DummyPaymentController::class, 'process'])->name('payments.dummy.process');
Route::get('/payments/receipt/{payment}', [DummyPaymentController::class, 'receipt'])->name('payments.receipt');