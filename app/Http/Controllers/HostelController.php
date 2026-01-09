<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class HostelController extends Controller
{
    /**
     * Display a listing of hostels with filtering and pagination
     */
    public function index(Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }
        
        try {
            Log::info('Hostels index accessed', [
                'filters' => $request->all(),
                'user_id' => auth()->id()
            ]);

            // Sample hostel data - replace with database queries when ready
            $allHostels = collect([
                (object) [
                    'id' => 1,
                    'name' => 'Kelaniya Boys Hostel',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'price' => 20000,
                    'formatted_price' => 'LKR 20,000',
                    'duration' => 'month',
                    'location' => 'Kelaniya',
                    'city' => 'Kelaniya',
                    'university' => 'University of Kelaniya',
                    'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Common Kitchen', 'Security'],
                    'rules' => ['No smoking', 'Visitors until 8 PM', 'Keep common areas clean'],
                    'description' => 'Budget-friendly hostel for Kelaniya University students.',
                    'capacity' => 50,
                    'available_slots' => 18,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(30),
                    'average_rating' => 4.0,
                    'total_reviews' => 33
                ],
                (object) [
                    'id' => 2,
                    'name' => 'NSBM Girls Hostel',
                    'type' => 'girls',
                    'type_display' => 'Girls Hostel',
                    'price' => 24000,
                    'formatted_price' => 'LKR 24,000',
                    'duration' => 'month',
                    'location' => 'Pitipana',
                    'city' => 'Pitipana',
                    'university' => 'NSBM',
                    'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Cafeteria', '24/7 Security'],
                    'rules' => ['Visitors register required', 'Maintain cleanliness', 'No pets'],
                    'description' => 'Modern girls hostel with study-friendly environment.',
                    'capacity' => 40,
                    'available_slots' => 5,
                    'availability_status' => 'Limited',
                    'created_at' => now()->subDays(25),
                    'average_rating' => 4.5,
                    'total_reviews' => 29
                ],
                (object) [
                    'id' => 3,
                    'name' => 'SLIIT Boys Hostel',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'price' => 22000,
                    'formatted_price' => 'LKR 22,000',
                    'duration' => 'month',
                    'location' => 'Malabe',
                    'city' => 'Malabe',
                    'university' => 'SLIIT',
                    'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Transport', 'Cafeteria'],
                    'rules' => ['No smoking', 'Quiet hours after 10 PM', 'ID required'],
                    'description' => 'Affordable boys hostel near SLIIT campus.',
                    'capacity' => 60,
                    'available_slots' => 18,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(20),
                    'average_rating' => 4.2,
                    'total_reviews' => 38
                ],
                (object) [
                    'id' => 4,
                    'name' => 'University Girls Hostel - Colombo',
                    'type' => 'girls',
                    'type_display' => 'Girls Hostel',
                    'price' => 28000,
                    'formatted_price' => 'LKR 28,000',
                    'duration' => 'month',
                    'location' => 'Colombo',
                    'city' => 'Colombo',
                    'university' => 'University of Colombo',
                    'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Gym', 'Security'],
                    'rules' => ['No male visitors after 8 PM', 'Curfew at 10 PM', 'Register visitors'],
                    'description' => 'Premium girls hostel in the heart of Colombo.',
                    'capacity' => 45,
                    'available_slots' => 8,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(15),
                    'average_rating' => 4.7,
                    'total_reviews' => 42
                ],
                (object) [
                    'id' => 5,
                    'name' => 'University Boys Hostel - Block A',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'price' => 25000,
                    'formatted_price' => 'LKR 25,000',
                    'duration' => 'month',
                    'location' => 'Colombo',
                    'city' => 'Colombo',
                    'university' => 'University of Colombo',
                    'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Cafeteria', 'Laundry', 'Parking', 'Security'],
                    'rules' => ['No smoking', 'Visitors until 9 PM', 'Maintain cleanliness'],
                    'description' => 'Modern boys hostel with excellent facilities near University of Colombo.',
                    'capacity' => 50,
                    'available_slots' => 12,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(10),
                    'average_rating' => 4.5,
                    'total_reviews' => 45
                ],
                (object) [
                    'id' => 6,
                    'name' => 'Mixed University Hostel - Kandy',
                    'type' => 'mixed',
                    'type_display' => 'Mixed Hostel',
                    'price' => 25000,
                    'formatted_price' => 'LKR 25,000',
                    'duration' => 'month',
                    'location' => 'Kandy',
                    'city' => 'Kandy',
                    'university' => 'University of Peradeniya',
                    'image_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Library', 'Recreation'],
                    'rules' => ['Separate floors for boys/girls', 'Visitors register required', 'Maintain discipline'],
                    'description' => 'Co-ed hostel for university students in Kandy.',
                    'capacity' => 80,
                    'available_slots' => 15,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(5),
                    'average_rating' => 4.3,
                    'total_reviews' => 56
                ]
            ]);

            // Apply filters
            $filteredHostels = $this->applyFilters($allHostels, $request);

            // Apply sorting
            $sortedHostels = $this->applySorting($filteredHostels, $request);

            // Paginate results
            $hostels = $this->paginateResults($sortedHostels, $request);

            // Get filter counts for display
            $filterCounts = [
                'total' => $allHostels->count(),
                'boys' => $allHostels->where('type', 'boys')->count(),
                'girls' => $allHostels->where('type', 'girls')->count(),
                'mixed' => $allHostels->where('type', 'mixed')->count(),
                'available' => $allHostels->where('available_slots', '>', 0)->count()
            ];

            return view('hostels.index', compact('hostels', 'filterCounts'));
        } catch (\Exception $e) {
            Log::error('Error in hostels index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return view('hostels.index', ['hostels' => collect(), 'filterCounts' => []]);
        }
    }

    /**
     * Display the specified hostel with detailed information
     */
    public function show($id)
    {
        try {
            Log::info('Showing hostel details', ['hostel_id' => $id]);

            $hostel = $this->getHostelById($id);

            if (!$hostel) {
                Log::warning('Hostel not found', ['hostel_id' => $id]);
                return redirect()->route('hostels')->with('error', 'Hostel not found.');
            }

            // Get similar hostels
            $similarHostels = $this->getSimilarHostels($id, $hostel);

            // Get packages for this hostel
            $packages = $this->getHostelPackages($id);

            return view('hostels.show', compact('hostel', 'similarHostels', 'packages'));
            
        } catch (\Exception $e) {
            Log::error('Error showing hostel: ' . $e->getMessage(), [
                'hostel_id' => $id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('hostels')->with('error', 'Hostel not found.');
        }
    }

    /**
     * Show booking form for a hostel
     */
    public function book($id)
    {
        Log::info('Booking method called', [
            'hostel_id' => $id,
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'request_url' => request()->url(),
            'request_method' => request()->method()
        ]);

        try {
            if (!Auth::check()) {
                Log::info('User not authenticated, redirecting to login');
                return redirect()->route('login')->with('message', 'Please login to book a hostel.');
            }

            $hostel = $this->getHostelById($id);

            if (!$hostel) {
                Log::error('Hostel not found', ['hostel_id' => $id]);
                return redirect()->route('hostels')->with('error', 'Hostel not found.');
            }

            if ($hostel->available_slots <= 0) {
                Log::warning('Hostel fully booked', ['hostel_id' => $id]);
                return redirect()->back()->with('error', 'Sorry, this hostel is fully booked.');
            }

            $packages = $this->getHostelPackages($id);
            $user = Auth::user();

            Log::info('Rendering booking view', [
                'hostel_id' => $id,
                'packages_count' => $packages->count(),
                'user_id' => $user->id
            ]);

            return view('bookings.create', compact('hostel', 'packages', 'user'));
        } catch (\Exception $e) {
            Log::error('Booking page error', [
                'hostel_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('hostels')->with('error', 'Unable to load booking page: ' . $e->getMessage());
        }
    }

    /**
     * Handle booking form submission from hostel page
     */
    public function createBooking(Request $request, $id)
    {
        Log::info('CreateBooking method called', [
            'hostel_id' => $id,
            'user_id' => Auth::id(),
            'request_data' => $request->except(['card_number', 'card_cvv'])
        ]);

        try {
            if (!Auth::check()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please log in to make a booking.',
                        'redirect' => route('login')
                    ], 401);
                }
                return redirect()->route('login')->with('message', 'Please login to book a hostel.');
            }

            // Add hostel_id to the request data
            $request->merge(['hostel_id' => $id]);
            
            // Forward to BookingController's store method if it exists
            if (class_exists('\App\Http\Controllers\BookingController')) {
                $bookingController = app(\App\Http\Controllers\BookingController::class);
                return $bookingController->store($request);
            } else {
                // Handle booking directly if BookingController doesn't exist
                return $this->processBooking($request, $id);
            }
            
        } catch (\Exception $e) {
            Log::error('Error in createBooking method', [
                'hostel_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server error occurred. Please try again.',
                    'error' => app()->environment('local') ? $e->getMessage() : null
                ], 500);
            }

            return back()->with('error', 'Failed to create booking. Please try again.')->withInput();
        }
    }

    /**
     * Process booking submission and redirect to payment
     */
    public function processBooking(Request $request, $id)
    {
        try {
            Log::info('Processing booking submission', [
                'hostel_id' => $id,
                'user_id' => auth()->id()
            ]);
            
            // Validate booking request
            $validated = $request->validate([
                'hostel_package_id' => 'required|integer',
                'check_in_date' => 'required|date|after:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|email',
                'guest_phone' => 'required|string|max:20',
                'special_requirements' => 'nullable|string|max:500'
            ]);
            
            // Create booking
            $booking = $this->createBookingRecord($validated, $id);
            
            Log::info('Booking created successfully', [
                'booking_id' => $booking['id'],
                'user_id' => auth()->id()
            ]);
            
            // Redirect to payment page or booking confirmation
            if (class_exists('\App\Http\Controllers\PaymentController')) {
                return redirect()->route('payments.show', $booking['id'])
                    ->with('success', 'Booking created! Please complete payment to confirm.');
            } else {
                return redirect()->route('bookings.show', $booking['id'])
                    ->with('success', 'Booking created successfully!');
            }
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Booking processing error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Booking failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Store booking (alternative method)
     */
    public function storeBooking(Request $request, $id)
    {
        Log::info('Store booking called', ['hostel_id' => $id, 'request_data' => $request->all()]);

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'package_id' => 'required|integer',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:500'
        ]);

        try {
            // Add hostel_id to request and forward to BookingController
            $request->merge(['hostel_id' => $id]);
            
            if (class_exists('\App\Http\Controllers\BookingController')) {
                return app(\App\Http\Controllers\BookingController::class)->store($request);
            } else {
                return $this->processBooking($request, $id);
            }
            
        } catch (\Exception $e) {
            Log::error('Booking storage error: ' . $e->getMessage());
            return back()->with('error', 'Failed to submit booking. Please try again.');
        }
    }

    /**
     * Show booking form (alternative route name)
     */
    public function showBookingForm($id)
    {
        return $this->book($id);
    }

    /**
     * Get hostel by ID
     */
    public function getHostelById($id)
    {
        $hostelsData = [
            1 => [
                'id' => 1,
                'name' => 'Kelaniya Boys Hostel',
                'type' => 'boys',
                'type_display' => 'Boys Hostel',
                'price' => 20000,
                'formatted_price' => 'LKR 20,000',
                'location' => 'Kelaniya',
                'university' => 'University of Kelaniya',
                'address' => '123 University Road, Kelaniya',
                'phone' => '+94 11 234 5678',
                'email' => 'info@kelaniahostel.lk',
                'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Common Kitchen', 'Laundry Service', 'Parking', 'Recreation Room', '24/7 Security', 'Backup Power'],
                'rules' => ['No smoking inside premises', 'Visitors allowed until 8:00 PM', 'Maintain cleanliness', 'No loud music after 10:00 PM', 'ID card required'],
                'description' => 'Budget-friendly hostel for Kelaniya University students with all essential amenities and a friendly environment.',
                'capacity' => 50,
                'available_slots' => 18,
                'availability_status' => 'Available',
                'average_rating' => 4.0,
                'total_reviews' => 33
            ],
            2 => [
                'id' => 2,
                'name' => 'NSBM Girls Hostel',
                'type' => 'girls',
                'type_display' => 'Girls Hostel',
                'price' => 24000,
                'formatted_price' => 'LKR 24,000',
                'location' => 'Pitipana',
                'university' => 'NSBM',
                'address' => '456 Campus Avenue, Pitipana',
                'phone' => '+94 11 765 4321',
                'email' => 'info@nsbmhostel.lk',
                'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Cafeteria', '24/7 Security', 'Library', 'Medical Room', 'Beauty Salon', 'Common Area'],
                'rules' => ['Visitors register required', 'Maintain room cleanliness', 'No pets allowed', 'Curfew guidelines', 'Respect others'],
                'description' => 'Modern girls hostel with study-friendly environment and excellent security features.',
                'capacity' => 40,
                'available_slots' => 5,
                'availability_status' => 'Limited',
                'average_rating' => 4.5,
                'total_reviews' => 29
            ],
            3 => [
                'id' => 3,
                'name' => 'SLIIT Boys Hostel',
                'type' => 'boys',
                'type_display' => 'Boys Hostel',
                'price' => 22000,
                'formatted_price' => 'LKR 22,000',
                'location' => 'Malabe',
                'university' => 'SLIIT',
                'address' => '789 SLIIT Road, Malabe',
                'phone' => '+94 11 456 7890',
                'email' => 'info@sliithostel.lk',
                'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Transport Service', 'Cafeteria', 'Recreation Area', 'Laundry', 'Security', 'Parking'],
                'rules' => ['No smoking in rooms', 'Quiet hours 10 PM - 6 AM', 'No alcohol', 'Respect others', 'ID required for entry'],
                'description' => 'Affordable boys hostel near SLIIT campus with transport facilities and modern amenities.',
                'capacity' => 60,
                'available_slots' => 18,
                'availability_status' => 'Available',
                'average_rating' => 4.2,
                'total_reviews' => 38
            ],
            4 => [
                'id' => 4,
                'name' => 'University Girls Hostel - Colombo',
                'type' => 'girls',
                'type_display' => 'Girls Hostel',
                'price' => 28000,
                'formatted_price' => 'LKR 28,000',
                'location' => 'Colombo',
                'university' => 'University of Colombo',
                'address' => '321 Reid Avenue, Colombo 07',
                'phone' => '+94 11 987 6543',
                'email' => 'info@colombogirlshostel.lk',
                'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Gym', '24/7 Security', 'Cafeteria', 'Library', 'Medical Room', 'Air Conditioning'],
                'rules' => ['No male visitors after 8:00 PM', 'Curfew at 10:00 PM', 'Register all visitors', 'No pets allowed', 'Maintain discipline'],
                'description' => 'Premium girls hostel in the heart of Colombo with luxury amenities and top-notch security.',
                'capacity' => 45,
                'available_slots' => 8,
                'availability_status' => 'Available',
                'average_rating' => 4.7,
                'total_reviews' => 42
            ],
            5 => [
                'id' => 5,
                'name' => 'University Boys Hostel - Block A',
                'type' => 'boys',
                'type_display' => 'Boys Hostel',
                'price' => 25000,
                'formatted_price' => 'LKR 25,000',
                'location' => 'Colombo',
                'university' => 'University of Colombo',
                'address' => '123 University Road, Colombo 03',
                'phone' => '+94 11 123 4567',
                'email' => 'boyshotel@university.lk',
                'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Cafeteria', 'Laundry Service', 'Parking', 'Recreation Room', 'Common Kitchen', '24/7 Security'],
                'rules' => ['No smoking inside premises', 'Visitors allowed until 9:00 PM', 'Maintain cleanliness', 'No loud music after 10:00 PM', 'ID card required'],
                'description' => 'Modern boys hostel with excellent facilities near University of Colombo.',
                'capacity' => 50,
                'available_slots' => 12,
                'availability_status' => 'Available',
                'average_rating' => 4.5,
                'total_reviews' => 45
            ],
            6 => [
                'id' => 6,
                'name' => 'Mixed University Hostel - Kandy',
                'type' => 'mixed',
                'type_display' => 'Mixed Hostel',
                'price' => 25000,
                'formatted_price' => 'LKR 25,000',
                'location' => 'Kandy',
                'university' => 'University of Peradeniya',
                'address' => '654 Peradeniya Road, Kandy',
                'phone' => '+94 81 234 5678',
                'email' => 'info@kandyhostel.lk',
                'image_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
                'images' => [
                    'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop',
                    'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=800&h=600&fit=crop'
                ],
                'facilities' => ['Free Wi-Fi', 'Study Room', 'Library', 'Recreation Area', 'Cafeteria', 'Transport', 'Security', 'Garden Area'],
                'rules' => ['Separate floors for boys/girls', 'Visitors register required', 'Maintain discipline', 'No smoking', 'Respect privacy'],
                'description' => 'Co-ed hostel for university students in Kandy with separate accommodation areas and modern facilities.',
                'capacity' => 80,
                'available_slots' => 15,
                'availability_status' => 'Available',
                'average_rating' => 4.3,
                'total_reviews' => 56
            ]
        ];

        $hostelData = $hostelsData[$id] ?? null;
        return $hostelData ? (object) $hostelData : null;
    }

    /**
     * Get packages for a hostel
     */
    public function getHostelPackages($hostelId)
    {
        return collect([
            (object) [
                'id' => 1,
                'hostel_id' => $hostelId,
                'name' => 'Standard Room',
                'type' => 'standard',
                'description' => 'Basic accommodation with essential amenities',
                'daily_price' => 800,
                'weekly_price' => 5000,
                'monthly_price' => 18000,
                'facilities' => 'Bed, Desk, Wardrobe, Shared Bathroom',
                'features' => ['Single Bed', 'Study Table', 'Wardrobe', 'WiFi']
            ],
            (object) [
                'id' => 2,
                'hostel_id' => $hostelId,  
                'name' => 'Premium Room',
                'type' => 'premium',
                'description' => 'Enhanced accommodation with additional amenities',
                'daily_price' => 1200,
                'weekly_price' => 7500,
                'monthly_price' => 25000,
                'facilities' => 'Bed, Desk, Wardrobe, Private Bathroom, AC',
                'features' => ['Single Bed', 'Study Table', 'Wardrobe', 'WiFi', 'AC', 'Attached Bathroom']
            ],
            (object) [
                'id' => 3,
                'hostel_id' => $hostelId,
                'name' => 'Deluxe Room',
                'type' => 'deluxe',
                'description' => 'Premium accommodation with all modern amenities',
                'daily_price' => 1500,
                'weekly_price' => 9500,
                'monthly_price' => 30000,
                'facilities' => 'Bed, Desk, Wardrobe, Private Bathroom, AC, Mini Fridge',
                'features' => ['Single Bed', 'Study Table', 'Wardrobe', 'WiFi', 'AC', 'Mini Fridge', 'Private Bathroom']
            ]
        ]);
    }

    /**
     * Get similar hostels based on type or location
     */
    private function getSimilarHostels($currentId, $hostel)
    {
        $allHostels = [
            1 => (object) ['id' => 1, 'name' => 'Kelaniya Boys Hostel', 'type' => 'boys', 'location' => 'Kelaniya', 'formatted_price' => 'LKR 20,000', 'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop', 'average_rating' => 4.0],
            2 => (object) ['id' => 2, 'name' => 'NSBM Girls Hostel', 'type' => 'girls', 'location' => 'Pitipana', 'formatted_price' => 'LKR 24,000', 'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop', 'average_rating' => 4.5],
            3 => (object) ['id' => 3, 'name' => 'SLIIT Boys Hostel', 'type' => 'boys', 'location' => 'Malabe', 'formatted_price' => 'LKR 22,000', 'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop', 'average_rating' => 4.2],
            4 => (object) ['id' => 4, 'name' => 'University Girls Hostel - Colombo', 'type' => 'girls', 'location' => 'Colombo', 'formatted_price' => 'LKR 28,000', 'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=300&fit=crop', 'average_rating' => 4.7],
            5 => (object) ['id' => 5, 'name' => 'University Boys Hostel - Block A', 'type' => 'boys', 'location' => 'Colombo', 'formatted_price' => 'LKR 25,000', 'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop', 'average_rating' => 4.5],
            6 => (object) ['id' => 6, 'name' => 'Mixed University Hostel - Kandy', 'type' => 'mixed', 'location' => 'Kandy', 'formatted_price' => 'LKR 25,000', 'image_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop', 'average_rating' => 4.3]
        ];

        return collect($allHostels)
            ->reject(function($h) use ($currentId) {
                return $h->id == $currentId;
            })
            ->filter(function($h) use ($hostel) {
                return $h->type === $hostel->type || $h->location === $hostel->location;
            })
            ->take(3);
    }

    /**
     * Create booking record
     */
    private function createBookingRecord($validated, $hostelId)
    {
        // Calculate duration and amount
        $checkIn = Carbon::parse($validated['check_in_date']);
        $checkOut = Carbon::parse($validated['check_out_date']);
        $daysDiff = $checkIn->diffInDays($checkOut);
        $months = max(1, ceil($daysDiff / 30));
        
        // Get package price
        $packagePrice = $this->getPackagePrice($validated['hostel_package_id']);
        $totalAmount = $packagePrice * $months;
        
        $bookingData = [
            'id' => rand(1000, 9999),
            'booking_reference' => 'BK-' . strtoupper(uniqid()),
            'user_id' => auth()->id(),
            'hostel_id' => $hostelId,
            'hostel_package_id' => $validated['hostel_package_id'],
            'check_in_date' => $validated['check_in_date'],
            'check_out_date' => $validated['check_out_date'],
            'duration' => $daysDiff,
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'],
            'special_requirements' => $validated['special_requirements'] ?? null,
            'amount' => $totalAmount,
            'total_amount' => $totalAmount,
            'booking_status' => 'pending',
            'payment_status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // Try to save to database if model exists
        if (class_exists('\App\Models\Booking')) {
            try {
                $booking = \App\Models\Booking::create($bookingData);
                return $booking->toArray();
            } catch (\Exception $e) {
                Log::warning('Could not save booking to database: ' . $e->getMessage());
            }
        }
        
        // Return sample data for demo
        return $bookingData;
    }
    
    /**
     * Get package price
     */
    private function getPackagePrice($packageId)
    {
        // Try to get real package price
        if (class_exists('\App\Models\HostelPackage')) {
            try {
                $package = \App\Models\HostelPackage::findOrFail($packageId);
                return $package->monthly_price;
            } catch (\Exception $e) {
                Log::warning('Could not fetch package price: ' . $e->getMessage());
            }
        }
        
        // Return sample prices
        $prices = [
            1 => 18000, // Standard Room
            2 => 25000, // Premium Room
            3 => 30000  // Deluxe Room
        ];
        
        return $prices[$packageId] ?? 18000;
    }

    /**
     * Apply filters to hostel collection
     */
    private function applyFilters($hostels, $request)
    {
        // Search filter
        if ($request->filled('search')) {
            $searchTerm = strtolower($request->search);
            $hostels = $hostels->filter(function ($hostel) use ($searchTerm) {
                return stripos($hostel->name, $searchTerm) !== false ||
                       stripos($hostel->location, $searchTerm) !== false ||
                       stripos($hostel->university, $searchTerm) !== false;
            });
        }

        // Type filter
        if ($request->filled('type') && $request->type !== 'all') {
            $hostels = $hostels->where('type', $request->type);
        }

        // Location filter
        if ($request->filled('location') && $request->location !== 'all') {
            $hostels = $hostels->where('location', $request->location);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $hostels = $hostels->where('price', '>=', (int)$request->min_price);
        }

        if ($request->filled('max_price')) {
            $hostels = $hostels->where('price', '<=', (int)$request->max_price);
        }

        // Availability filter
        if ($request->filled('availability') && $request->availability === 'available') {
            $hostels = $hostels->where('available_slots', '>', 0);
        }

        return $hostels;
    }

    /**
     * Apply sorting to hostel collection
     */
    private function applySorting($hostels, $request)
    {
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');

        switch ($sortBy) {
            case 'price':
                $hostels = $sortOrder === 'desc' 
                    ? $hostels->sortByDesc('price')
                    : $hostels->sortBy('price');
                break;
            case 'rating':
                $hostels = $hostels->sortByDesc('average_rating');
                break;
            case 'availability':
                $hostels = $hostels->sortByDesc('available_slots');
                break;
            case 'newest':
                $hostels = $hostels->sortByDesc('created_at');
                break;
            default:
                $hostels = $hostels->sortBy('name');
        }

        return $hostels;
    }

    /**
     * Paginate results
     */
    private function paginateResults($hostels, $request)
    {
        $perPage = $request->get('per_page', 12);
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $hostelsArray = $hostels->values()->all();
        $currentItems = array_slice($hostelsArray, ($currentPage - 1) * $perPage, $perPage);

        $paginator = new LengthAwarePaginator(
            collect($currentItems),
            count($hostelsArray),
            $perPage,
            $currentPage,
            [
                'path' => $request->url(),
                'pageName' => 'page'
            ]
        );

        $paginator->appends($request->all());
        return $paginator;
    }

    // ==============================================
    // ADDITIONAL UTILITY AND API METHODS
    // ==============================================

    /**
     * Search hostels via AJAX
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            $hostels = collect([
                ['id' => 1, 'name' => 'Kelaniya Boys Hostel', 'location' => 'Kelaniya', 'price' => 20000],
                ['id' => 2, 'name' => 'NSBM Girls Hostel', 'location' => 'Pitipana', 'price' => 24000],
                ['id' => 3, 'name' => 'SLIIT Boys Hostel', 'location' => 'Malabe', 'price' => 22000],
                ['id' => 4, 'name' => 'University Girls Hostel - Colombo', 'location' => 'Colombo', 'price' => 28000],
                ['id' => 5, 'name' => 'University Boys Hostel - Block A', 'location' => 'Colombo', 'price' => 25000],
                ['id' => 6, 'name' => 'Mixed University Hostel - Kandy', 'location' => 'Kandy', 'price' => 25000]
            ]);

            if (!empty($query)) {
                $hostels = $hostels->filter(function ($hostel) use ($query) {
                    return stripos($hostel['name'], $query) !== false ||
                           stripos($hostel['location'], $query) !== false;
                });
            }

            return response()->json([
                'success' => true,
                'hostels' => $hostels->values()
            ]);
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Search failed'
            ], 500);
        }
    }

    /**
     * Filter hostels (for additional filtering functionality)
     */
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Get hostels by city
     */
    public function byCity($city, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }
        $request->merge(['location' => $city]);
        return $this->index($request);
    }

    /**
     * Get hostels by type
     */
    public function byType($type, Request $request = null)
    {
        if ($request === null) {
            $request = request();
        }
        $request->merge(['type' => $type]);
        return $this->index($request);
    }

    /**
     * Check hostel availability for specific dates
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'hostel_id' => 'required|integer',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date'
        ]);

        try {
            $hostel = $this->getHostelById($request->hostel_id);
            
            if (!$hostel) {
                return response()->json([
                    'available' => false,
                    'message' => 'Hostel not found'
                ], 404);
            }

            // For demo purposes, assume availability based on current slots
            $available = $hostel->available_slots > 0;

            return response()->json([
                'available' => $available,
                'message' => $available 
                    ? 'Dates are available!' 
                    : 'Selected dates are not available.',
                'available_slots' => $hostel->available_slots,
                'hostel_name' => $hostel->name
            ]);
        } catch (\Exception $e) {
            Log::error('Availability check error: ' . $e->getMessage());
            return response()->json([
                'available' => false,
                'message' => 'Error checking availability. Please try again.'
            ], 500);
        }
    }

    /**
     * Get featured hostels for homepage
     */
    public function getFeatured()
    {
        try {
            // Return top-rated hostels
            $featured = collect([
                (object) [
                    'id' => 4,
                    'name' => 'University Girls Hostel - Colombo',
                    'price' => 28000,
                    'formatted_price' => 'LKR 28,000',
                    'location' => 'Colombo',
                    'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=300&fit=crop',
                    'average_rating' => 4.7,
                    'available_slots' => 8
                ],
                (object) [
                    'id' => 5,
                    'name' => 'University Boys Hostel - Block A',
                    'price' => 25000,
                    'formatted_price' => 'LKR 25,000',
                    'location' => 'Colombo',
                    'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop',
                    'average_rating' => 4.5,
                    'available_slots' => 12
                ],
                (object) [
                    'id' => 2,
                    'name' => 'NSBM Girls Hostel',
                    'price' => 24000,
                    'formatted_price' => 'LKR 24,000',
                    'location' => 'Pitipana',
                    'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop',
                    'average_rating' => 4.5,
                    'available_slots' => 5
                ]
            ]);

            return response()->json([
                'success' => true,
                'hostels' => $featured
            ]);
        } catch (\Exception $e) {
            Log::error('Featured hostels error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load featured hostels'
            ], 500);
        }
    }

    /**
     * Get available locations for filter dropdown
     */
    public function getLocations()
    {
        $locations = [
            'Colombo', 'Kelaniya', 'Pitipana', 'Malabe', 'Kandy', 'Galle'
        ];

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);
    }

    /**
     * Get hostel statistics for dashboard
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_hostels' => 6,
                'boys_hostels' => 3,
                'girls_hostels' => 2,
                'mixed_hostels' => 1,
                'available_slots' => 76,
                'total_capacity' => 325,
                'average_price' => 24000,
                'locations' => 6
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * API search for AJAX calls (alias)
     */
    public function apiSearch(Request $request)
    {
        return $this->search($request);
    }

    /**
     * Debug method to test if controller works
     */
    public function debug($id)
    {
        return response()->json([
            'controller_working' => true,
            'hostel_id' => $id,
            'hostel_data' => $this->getHostelById($id),
            'packages' => $this->getHostelPackages($id),
            'auth_status' => Auth::check(),
            'user' => Auth::user(),
            'route_name' => request()->route()->getName(),
            'current_url' => request()->url(),
            'method' => request()->method(),
            'timestamp' => now()->toDateTimeString(),
            'available_methods' => [
                'book' => 'GET /hostels/{id}/book',
                'createBooking' => 'POST /hostels/{id}/book',
                'storeBooking' => 'Alternative booking method',
                'processBooking' => 'Internal booking processing',
                'showBookingForm' => 'Alternative booking form route'
            ]
        ]);
    }
}