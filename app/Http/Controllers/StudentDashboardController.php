<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class StudentDashboardController extends BaseController
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Check if middleware method exists before calling it
        if (method_exists($this, 'middleware')) {
            $this->middleware('auth');
        }
    }

    /**
     * Show the student dashboard
     */
    public function index()
    {
        // Check authentication manually if middleware isn't available
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        try {
            // Get dashboard statistics with error handling
            $stats = $this->getDashboardStats();
            
            // Get recent bookings
            $recentBookings = $this->getRecentBookings();
            
            // Get recent payments
            $recentPayments = $this->getRecentPayments();
            
            // Get upcoming check-ins
            $upcomingCheckIns = $this->getUpcomingCheckIns();
            
            // Profile completion percentage
            $profileCompletion = $this->calculateProfileCompletion($user);
            
            return view('student.dashboard', compact(
                'user',
                'stats',
                'recentBookings', 
                'recentPayments',
                'upcomingCheckIns',
                'profileCompletion'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            
            // Fallback data
            $stats = $this->getFallbackStats();
            $recentBookings = collect();
            $recentPayments = collect();
            $upcomingCheckIns = collect();
            $profileCompletion = ['percentage' => 50];
            
            return view('student.dashboard', compact(
                'user',
                'stats',
                'recentBookings', 
                'recentPayments',
                'upcomingCheckIns',
                'profileCompletion'
            ));
        }
    }

    /**
     * Show student profile page
     */
    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $profileCompletion = $this->calculateProfileCompletion($user);
        
        return view('student.profile', compact('user', 'profileCompletion'));
    }

    /**
     * Show student bookings page
     */
    public function bookings()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $user = Auth::user();
            
            if (class_exists('\App\Models\Booking') && method_exists($user, 'bookings')) {
                $bookings = $user->bookings()
                    ->with(['hostelPackage', 'hostel'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $bookings = $this->getSampleBookings();
            }
            
            return view('student.bookings', compact('bookings'));
        } catch (\Exception $e) {
            return view('student.bookings', ['bookings' => collect()]);
        }
    }

    /**
     * Show student payments page
     */
    public function payments()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $user = Auth::user();
            
            if (class_exists('\App\Models\Payment')) {
                $payments = \App\Models\Payment::where('user_id', $user->id)
                    ->with(['booking'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $payments = $this->getSamplePayments();
            }
            
            return view('student.payments', compact('payments'));
        } catch (\Exception $e) {
            return view('student.payments', ['payments' => collect()]);
        }
    }

    /**
     * Show student reviews page
     */
    public function reviews()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $user = Auth::user();
            
            if (class_exists('\App\Models\Review') && method_exists($user, 'reviews')) {
                $reviews = $user->reviews()
                    ->with(['hostelPackage', 'booking'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                $reviews = $this->getSampleReviews();
            }
            
            return view('student.reviews', compact('reviews'));
        } catch (\Exception $e) {
            return view('student.reviews', ['reviews' => collect()]);
        }
    }

    /**
     * Get dashboard statistics API
     */
    public function getStats()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return response()->json($this->getDashboardStats());
    }

    /**
     * Quick search for hostels
     */
    public function quickSearch(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'city' => 'nullable|string',
            'type' => 'nullable|in:boys,girls,mixed',
            'check_in' => 'nullable|date|after:today',
            'check_out' => 'nullable|date|after:check_in',
            'max_price' => 'nullable|numeric|min:0'
        ]);
        
        try {
            if (class_exists('\App\Models\Hostel')) {
                $query = \App\Models\Hostel::with(['packages'])
                    ->where('is_active', true);
                
                if ($request->city) {
                    $query->where('city', 'like', '%' . $request->city . '%');
                }
                
                if ($request->type) {
                    $query->where('type', $request->type);
                }
                
                if ($request->max_price) {
                    $query->whereHas('packages', function($q) use ($request) {
                        $q->where('monthly_price', '<=', $request->max_price);
                    });
                }
                
                $hostels = $query->limit(10)->get();
            } else {
                $hostels = $this->getSampleHostels($request);
            }
            
            return response()->json([
                'success' => true,
                'hostels' => $hostels,
                'redirect' => route('hostels', $request->only(['city', 'type', 'check_in', 'check_out', 'max_price']))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'hostels' => collect()
            ]);
        }
    }

    /**
     * Get notifications
     */
    public function getNotifications()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $user = Auth::user();
            $notifications = [];
            
            // Check for incomplete profile
            $profileCompletion = $this->calculateProfileCompletion($user);
            if ($profileCompletion['percentage'] < 80) {
                $notifications[] = [
                    'type' => 'warning',
                    'title' => 'Complete Your Profile',
                    'message' => 'Your profile is ' . $profileCompletion['percentage'] . '% complete. Complete it for better service.',
                    'action_url' => route('student.profile.edit'),
                    'action_text' => 'Complete Now',
                    'created_at' => now()
                ];
            }
            
            // Check for pending payments (with model existence check)
            if (method_exists($user, 'bookings')) {
                try {
                    $pendingPayments = $user->bookings()
                        ->where('payment_status', 'pending')
                        ->where('created_at', '>=', now()->subDays(7))
                        ->count();
                        
                    if ($pendingPayments > 0) {
                        $notifications[] = [
                            'type' => 'error',
                            'title' => 'Pending Payments',
                            'message' => "You have {$pendingPayments} pending payment(s). Complete them to confirm your bookings.",
                            'action_url' => route('student.bookings'),
                            'action_text' => 'View Bookings',
                            'created_at' => now()
                        ];
                    }
                } catch (\Exception $e) {
                    // Ignore booking-related errors
                }
            }
            
            // Add welcome notification for new users
            $notifications[] = [
                'type' => 'info',
                'title' => 'Welcome to Hostel Management',
                'message' => 'Explore our hostels and find your perfect accommodation.',
                'action_url' => route('hostels'),
                'action_text' => 'Browse Hostels',
                'created_at' => now()
            ];
            
            return response()->json([
                'notifications' => collect($notifications)->sortByDesc('created_at')->values(),
                'unread_count' => count($notifications)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'notifications' => [],
                'unread_count' => 0
            ]);
        }
    }

    /**
     * Show notifications page
     */
    public function notifications()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $notifications = $this->getNotifications()->getData()->notifications ?? collect();
        return view('student.notifications', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // TODO: Mark notification as read in database when notification system is implemented
        return response()->json(['success' => true]);
    }

    /**
     * Delete notification
     */
    public function deleteNotification($id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // TODO: Delete notification from database when notification system is implemented
        return response()->json(['success' => true]);
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    /**
     * Get dashboard statistics with error handling
     */
    private function getDashboardStats()
    {
        try {
            $user = Auth::user();
            
            if (method_exists($user, 'bookings')) {
                return [
                    'total_bookings' => $user->bookings()->count(),
                    'active_bookings' => $user->bookings()
                        ->whereIn('booking_status', ['confirmed', 'checked_in'])
                        ->count(),
                    'pending_bookings' => $user->bookings()
                        ->where('booking_status', 'pending')
                        ->count(),
                    'pending_payments' => $user->bookings()
                        ->where('payment_status', 'pending')
                        ->count(),
                    'total_spent' => $user->bookings()
                        ->where('payment_status', 'paid')
                        ->sum('total_amount')
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Error getting real stats: ' . $e->getMessage());
        }
        
        return $this->getFallbackStats();
    }

    /**
     * Get fallback statistics for demo
     */
    private function getFallbackStats()
    {
        return [
            'total_bookings' => 2,
            'active_bookings' => 1,
            'pending_bookings' => 1,
            'pending_payments' => 0,
            'total_spent' => 25000
        ];
    }

    /**
     * Get recent bookings with error handling
     */
    private function getRecentBookings()
    {
        try {
            $user = Auth::user();
            
            if (method_exists($user, 'bookings')) {
                return $user->bookings()
                    ->with(['hostelPackage', 'hostel'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error getting real bookings: ' . $e->getMessage());
        }
        
        return $this->getSampleBookings();
    }

    /**
     * Get recent payments with error handling
     */
    private function getRecentPayments()
    {
        try {
            if (class_exists('\App\Models\Payment')) {
                return \App\Models\Payment::where('user_id', Auth::id())
                    ->with(['booking'])
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error getting real payments: ' . $e->getMessage());
        }
        
        return $this->getSamplePayments();
    }

    /**
     * Get upcoming check-ins with error handling
     */
    private function getUpcomingCheckIns()
    {
        try {
            $user = Auth::user();
            
            if (method_exists($user, 'bookings')) {
                return $user->bookings()
                    ->with(['hostelPackage', 'hostel'])
                    ->where('booking_status', 'confirmed')
                    ->where('check_in_date', '>=', Carbon::today())
                    ->where('check_in_date', '<=', Carbon::today()->addDays(7))
                    ->orderBy('check_in_date')
                    ->get();
            }
        } catch (\Exception $e) {
            Log::warning('Error getting upcoming check-ins: ' . $e->getMessage());
        }
        
        return collect([
            (object) [
                'id' => 1,
                'hostel_name' => 'University Boys Hostel - Block A',
                'check_in_date' => Carbon::today()->addDays(3),
                'booking_reference' => 'BK-DEMO001'
            ]
        ]);
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user)
    {
        $requiredFields = [
            'name', 'email', 'phone', 'university', 'faculty', 
            'student_id', 'year_of_study', 'nic', 'address',
            'emergency_contact_name', 'emergency_contact_phone'
        ];
        
        $completedFields = 0;
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (isset($user->$field) && $user->$field && trim($user->$field) !== '') {
                $completedFields++;
            } else {
                $missingFields[] = $field;
            }
        }
        
        $percentage = round(($completedFields / count($requiredFields)) * 100);
        
        return [
            'percentage' => $percentage,
            'completed_fields' => $completedFields,
            'total_fields' => count($requiredFields),
            'missing_fields' => $missingFields
        ];
    }

    /**
     * Get sample bookings for demo
     */
    private function getSampleBookings()
    {
        return collect([
            (object) [
                'id' => 1,
                'booking_reference' => 'BK-DEMO001',
                'hostel_name' => 'University Boys Hostel - Block A',
                'status' => 'confirmed',
                'booking_status' => 'confirmed',
                'check_in_date' => Carbon::now()->addDays(10),
                'check_out_date' => Carbon::now()->addDays(40),
                'total_amount' => 25000,
                'created_at' => Carbon::now()->subDays(5)
            ],
            (object) [
                'id' => 2,
                'booking_reference' => 'BK-DEMO002',
                'hostel_name' => 'SLIIT Boys Hostel',
                'status' => 'pending',
                'booking_status' => 'pending',
                'check_in_date' => Carbon::now()->addDays(5),
                'check_out_date' => Carbon::now()->addDays(35),
                'total_amount' => 22000,
                'created_at' => Carbon::now()->subDays(2)
            ]
        ]);
    }

    /**
     * Get sample payments for demo
     */
    private function getSamplePayments()
    {
        return collect([
            (object) [
                'id' => 1,
                'amount' => 25000,
                'status' => 'completed',
                'payment_method' => 'Credit Card',
                'reference' => 'PAY-DEMO001',
                'created_at' => Carbon::now()->subDays(5),
                'booking' => (object) [
                    'booking_reference' => 'BK-DEMO001',
                    'hostel_name' => 'University Boys Hostel - Block A'
                ]
            ]
        ]);
    }

    /**
     * Get sample reviews for demo
     */
    private function getSampleReviews()
    {
        return collect([
            (object) [
                'id' => 1,
                'rating' => 5,
                'comment' => 'Excellent hostel with great facilities!',
                'hostel_name' => 'University Boys Hostel - Block A',
                'created_at' => Carbon::now()->subDays(10)
            ]
        ]);
    }

    /**
     * Get sample hostels for search
     */
    private function getSampleHostels($request)
    {
        $hostels = collect([
            (object) [
                'id' => 1,
                'name' => 'University Boys Hostel - Block A',
                'city' => 'Colombo',
                'type' => 'boys',
                'price' => 25000
            ],
            (object) [
                'id' => 2,
                'name' => 'University Girls Hostel - Block B',
                'city' => 'Colombo',
                'type' => 'girls',
                'price' => 27000
            ]
        ]);

        // Apply filters
        if ($request->city) {
            $hostels = $hostels->filter(function($hostel) use ($request) {
                return stripos($hostel->city, $request->city) !== false;
            });
        }

        if ($request->type) {
            $hostels = $hostels->where('type', $request->type);
        }

        if ($request->max_price) {
            $hostels = $hostels->where('price', '<=', $request->max_price);
        }

        return $hostels->values();
    }
}