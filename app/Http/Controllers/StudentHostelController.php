<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hostel;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentHostelController extends Controller
{
    /**
     * Display a listing of available hostels for students
     */
    public function index()  // ← FIXED: Removed Request parameter
    {
        // Get request data using request() helper instead
        $request = request();
        
        $query = Hostel::with(['packages', 'reviews'])
            ->where('is_active', true)
            ->where('available_slots', '>', 0);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        
        // Sorting
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        switch ($sortField) {
            case 'price':
                $query->orderBy('price', $sortDirection);
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')
                      ->orderBy('reviews_avg_rating', $sortDirection);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortDirection);
                break;
            default:
                $query->orderBy('name', $sortDirection);
        }
        
        $hostels = $query->paginate(12)->withQueryString();
        
        return view('student.hostels.index', compact('hostels'));
    }
    
    /**
     * Display the specified hostel for students
     */
    public function show($id)
    {
        $hostel = Hostel::with(['packages', 'reviews.user'])
            ->where('is_active', true)
            ->findOrFail($id);
        
        // Get reviews with pagination
        $reviews = Review::where('hostel_id', $hostel->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get similar hostels
        $similarHostels = Hostel::where('type', $hostel->type)
            ->where('id', '!=', $hostel->id)
            ->where('is_active', true)
            ->where('available_slots', '>', 0)
            ->limit(3)
            ->get();
        
        // Check if user has already booked this hostel
        $userBooking = null;
        if (Auth::check()) {
            $userBooking = Booking::where('user_id', Auth::id())
                ->where('hostel_id', $hostel->id)
                ->whereIn('booking_status', ['pending', 'confirmed', 'checked_in'])
                ->first();
        }
        
        return view('student.hostels.show', compact(
            'hostel', 
            'reviews', 
            'similarHostels', 
            'userBooking'
        ));
    }
    
    /**
     * Show the booking form for the specified hostel
     */
    public function book($id)
    {
        $hostel = Hostel::with('packages')
            ->where('is_active', true)
            ->where('available_slots', '>', 0)
            ->findOrFail($id);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to book a hostel.');
        }
        
        // Check if user already has an active booking for this hostel
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('hostel_id', $hostel->id)
            ->whereIn('booking_status', ['pending', 'confirmed', 'checked_in'])
            ->first();
        
        if ($existingBooking) {
            return redirect()->route('student.hostels.show', $hostel->id)
                ->with('error', 'You already have an active booking for this hostel.');
        }
        
        $user = Auth::user();
        
        return view('student.hostels.book', compact('hostel', 'user'));
    }
    
    /**
     * Store a new booking
     */
    public function storeBooking(Request $request, $id)
    {
        $hostel = Hostel::findOrFail($id);
        
        // Validate request
        $request->validate([
            'package_id' => 'required|exists:hostel_packages,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'special_requests' => 'nullable|string|max:500',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'terms_accepted' => 'required|accepted'
        ]);
        
        // Check if hostel is still available
        if ($hostel->available_slots <= 0) {
            return redirect()->back()
                ->with('error', 'This hostel is no longer available.');
        }
        
        // Check for existing booking
        $existingBooking = Booking::where('user_id', Auth::id())
            ->where('hostel_id', $hostel->id)
            ->whereIn('booking_status', ['pending', 'confirmed', 'checked_in'])
            ->first();
        
        if ($existingBooking) {
            return redirect()->back()
                ->with('error', 'You already have an active booking for this hostel.');
        }
        
        DB::beginTransaction();
        
        try {
            // Get the selected package
            $package = $hostel->packages()->findOrFail($request->package_id);
            
            // Calculate duration and total amount
            $checkIn = Carbon::parse($request->check_in_date);
            $checkOut = Carbon::parse($request->check_out_date);
            $duration = $checkIn->diffInDays($checkOut);
            
            // Calculate total amount based on package pricing
            $totalAmount = $this->calculateTotalAmount($package, $duration);
            
            // Generate booking reference
            $bookingReference = 'BK-' . strtoupper(uniqid());
            
            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hostel_id' => $hostel->id,
                'hostel_package_id' => $package->id,
                'booking_reference' => $bookingReference,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'duration_days' => $duration,
                'total_amount' => $totalAmount,
                'booking_status' => 'pending',
                'payment_status' => 'pending',
                'special_requests' => $request->special_requests,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'booked_at' => now()
            ]);
            
            // Update hostel available slots
            $hostel->decrement('available_slots');
            
            DB::commit();
            
            // Redirect to payment page
            return redirect()->route('payments.show', $booking->id)
                ->with('success', 'Booking created successfully! Please complete the payment to confirm your booking.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                ->with('error', 'Failed to create booking. Please try again.')
                ->withInput();
        }
    }
    
    /**
     * Calculate total amount based on package and duration
     */
    private function calculateTotalAmount($package, $duration)
    {
        switch ($package->billing_type) {
            case 'daily':
                return $package->daily_price * $duration;
            case 'weekly':
                $weeks = ceil($duration / 7);
                return $package->weekly_price * $weeks;
            case 'monthly':
                $months = ceil($duration / 30);
                return $package->monthly_price * $months;
            default:
                return $package->monthly_price; // Default to monthly
        }
    }
    
    /**
     * Search hostels via AJAX
     */
    public function search(Request $request)
    {
        $query = Hostel::where('is_active', true)
            ->where('available_slots', '>', 0);
        
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $hostels = $query->limit(10)->get(['id', 'name', 'location', 'price', 'type']);
        
        return response()->json([
            'success' => true,
            'hostels' => $hostels
        ]);
    }
    
    /**
     * Get available packages for a hostel
     */
    public function getPackages($hostelId)
    {
        $hostel = Hostel::with('packages')->findOrFail($hostelId);
        
        return response()->json([
            'success' => true,
            'packages' => $hostel->packages
        ]);
    }
    
    /**
     * Check availability for booking dates
     */
    public function checkAvailability(Request $request, $hostelId)
    {
        $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date'
        ]);
        
        $hostel = Hostel::findOrFail($hostelId);
        
        // Check if there are conflicting bookings
        $conflictingBookings = Booking::where('hostel_id', $hostelId)
            ->whereIn('booking_status', ['confirmed', 'checked_in'])
            ->where(function($query) use ($request) {
                $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                      ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('check_in_date', '<=', $request->check_in_date)
                            ->where('check_out_date', '>=', $request->check_out_date);
                      });
            })
            ->count();
        
        $availableSlots = max(0, $hostel->capacity - $conflictingBookings);
        
        return response()->json([
            'available' => $availableSlots > 0,
            'available_slots' => $availableSlots,
            'total_capacity' => $hostel->capacity
        ]);
    }
}