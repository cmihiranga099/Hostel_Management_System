<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        try {
            // Try to get real data from database, fallback to sample data if models don't exist
            $boysHostels = $this->getSampleHostels('boys')->map(function($hostel) {
                return $this->ensureHostelProperties($hostel);
            });
            
            $girlsHostels = $this->getSampleHostels('girls')->map(function($hostel) {
                return $this->ensureHostelProperties($hostel);
            });
            
            $recentReviews = $this->getSampleReviews()->map(function($review) {
                return $this->ensureReviewProperties($review);
            });
            
            // Get statistics with error handling
            $stats = $this->getStats();

            return view('home.index', compact('boysHostels', 'girlsHostels', 'recentReviews', 'stats'));
        } catch (\Exception $e) {
            // Fallback to simple view if database models aren't ready
            \Log::error('Error in home index: ' . $e->getMessage());
            $stats = $this->getSampleStats();
            return view('home', compact('stats'));
        }
    }

    /**
     * Display hostels listing page
     */
    public function hostels(Request $request)
    {
        try {
            // Try to get real data from HostelPackage model
            if (class_exists('\App\Models\HostelPackage')) {
                $query = \App\Models\HostelPackage::where('is_active', true)->where('available_slots', '>', 0);

                // Filter by type
                if ($request->filled('type') && in_array($request->type, ['boys', 'girls'])) {
                    $query->where('type', $request->type);
                }

                // Filter by price range
                if ($request->filled('min_price')) {
                    $query->where('price', '>=', $request->min_price);
                }
                if ($request->filled('max_price')) {
                    $query->where('price', '<=', $request->max_price);
                }

                // Search
                if ($request->filled('search')) {
                    $search = $request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('description', 'like', "%{$search}%");
                    });
                }

                // Sort
                $sortBy = $request->get('sort', 'name');
                $sortOrder = $request->get('order', 'asc');
                
                if (in_array($sortBy, ['name', 'price', 'created_at'])) {
                    $query->orderBy($sortBy, $sortOrder);
                }

                $hostels = $query->paginate(12);
                return view('home.hostels', compact('hostels'));
            }
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::warning('Error loading hostels from database: ' . $e->getMessage());
        }

        // Fallback to HostelController if HostelPackage model doesn't exist
        return app(\App\Http\Controllers\HostelController::class)->index($request);
    }

    /**
     * Display hostel details
     */
    public function hostelDetails($id)
    {
        try {
            if (class_exists('\App\Models\HostelPackage')) {
                $hostel = \App\Models\HostelPackage::where('is_active', true)->findOrFail($id);
                
                // Get reviews for this hostel
                $reviews = \App\Models\Review::where('is_approved', true)
                    ->where('hostel_package_id', $id)
                    ->with('user')
                    ->latest()
                    ->paginate(10);

                // Get similar hostels
                $similarHostels = \App\Models\HostelPackage::where('is_active', true)
                    ->where('available_slots', '>', 0)
                    ->where('type', $hostel->type)
                    ->where('id', '!=', $id)
                    ->take(3)
                    ->get();

                return view('home.hostel-details', compact('hostel', 'reviews', 'similarHostels'));
            }
        } catch (\Exception $e) {
            \Log::warning('Error loading hostel details: ' . $e->getMessage());
        }

        // Fallback to HostelController
        return app(\App\Http\Controllers\HostelController::class)->show($id);
    }

    /**
     * Display the about page
     */
    public function about()
    {
        $stats = [
            'established_year' => '2018',
            'total_students' => $this->getSafeCount('\App\Models\User', 1200),
            'total_hostels' => $this->getSafeCount('\App\Models\HostelPackage', 25),
            'success_rate' => '98.5%',
            'cities_covered' => 15,
            'universities_partnered' => 8,
            'total_bookings' => $this->getSafeCount('\App\Models\Booking', 950),
            'satisfaction_rate' => '96%'
        ];

        // Try different view paths
        $viewPaths = [
            'about',           // resources/views/about.blade.php
            'pages.about',     // resources/views/pages/about.blade.php
            'home.about',      // resources/views/home/about.blade.php
        ];

        foreach ($viewPaths as $viewPath) {
            if (view()->exists($viewPath)) {
                return view($viewPath, compact('stats'));
            }
        }

        // If no view exists, create a simple one inline
        return response()->view('errors.custom', [
            'title' => 'About Us',
            'message' => 'About page is under construction.',
            'stats' => $stats
        ], 200);
    }

    /**
     * Display contact page
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        // In a real application, you would send an email or store in database
        // For now, we'll just return a success response
        
        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Display reviews page
     */
    public function reviews()
    {
        try {
            if (class_exists('\App\Models\Review')) {
                $reviews = \App\Models\Review::where('is_approved', true)
                    ->with(['user', 'hostelPackage'])
                    ->latest()
                    ->paginate(15);

                return view('reviews', compact('reviews'));
            }
        } catch (\Exception $e) {
            \Log::warning('Error loading reviews: ' . $e->getMessage());
        }

        // Fallback to sample reviews
        $reviews = $this->getSampleReviews();
        return view('reviews', compact('reviews'));
    }

    /**
     * Display help page
     */
    public function help()
    {
        return view('help');
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $faqs = [
            [
                'question' => 'How do I book a hostel?',
                'answer' => 'You can browse available hostels, select one that suits your needs, and complete the booking process online with secure payment.'
            ],
            [
                'question' => 'What payment methods are accepted?',
                'answer' => 'We accept major credit cards, debit cards, and online banking transfers.'
            ],
            [
                'question' => 'Can I cancel my booking?',
                'answer' => 'Yes, you can cancel your booking according to our cancellation policy. Please check the terms and conditions for details.'
            ]
        ];

        return view('faq', compact('faqs'));
    }

    /**
     * Display privacy policy
     */
    public function privacyPolicy()
    {
        return view('legal.privacy-policy');
    }

    /**
     * Display terms of service
     */
    public function termsOfService()
    {
        return view('legal.terms-of-service');
    }

    /**
     * Display cookie policy
     */
    public function cookiePolicy()
    {
        return view('legal.cookie-policy');
    }

    /**
     * Generate sitemap
     */
    public function sitemap()
    {
        $urls = [
            route('home'),
            route('about'),
            route('contact'),
            route('hostels'),
            route('reviews')
        ];

        return response()->view('sitemap', compact('urls'))
                         ->header('Content-Type', 'text/xml');
    }

    /**
     * Generate robots.txt
     */
    public function robots()
    {
        $content = "User-agent: *\nAllow: /\nSitemap: " . route('sitemap');
        
        return response($content)
               ->header('Content-Type', 'text/plain');
    }

    /**
     * Handle email delivery webhook
     */
    public function emailDelivered(Request $request)
    {
        // Log email delivery
        \Log::info('Email delivered', $request->all());
        
        return response()->json(['status' => 'success']);
    }

    /**
     * Handle email bounce webhook
     */
    public function emailBounced(Request $request)
    {
        // Log email bounce
        \Log::warning('Email bounced', $request->all());
        
        return response()->json(['status' => 'success']);
    }

    // ==========================================
    // PRIVATE HELPER METHODS
    // ==========================================

    /**
     * Get sample hostels data
     */
    private function getSampleHostels($type)
    {
        $hostels = [
            'boys' => [
                (object) [
                    'id' => 1,
                    'name' => 'University Boys Hostel - Block A',
                    'price' => 25000,
                    'formatted_price' => 'LKR 25,000',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'duration' => 'month',
                    'available_slots' => 12,
                    'capacity' => 50,
                    'image_url' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=400&h=300&fit=crop',
                    'location' => 'Colombo',
                    'university' => 'University of Colombo',
                    'description' => 'Modern boys hostel with excellent facilities near University of Colombo.',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Cafeteria', 'Laundry', 'Parking', 'Security'],
                    'average_rating' => 4.5,
                    'total_reviews' => 45,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(30)
                ],
                (object) [
                    'id' => 3,
                    'name' => 'SLIIT Boys Hostel',
                    'price' => 22000,
                    'formatted_price' => 'LKR 22,000',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'duration' => 'month',
                    'available_slots' => 18,
                    'capacity' => 60,
                    'image_url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400&h=300&fit=crop',
                    'location' => 'Malabe',
                    'university' => 'SLIIT',
                    'description' => 'Affordable boys hostel near SLIIT campus.',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Transport', 'Cafeteria'],
                    'average_rating' => 4.2,
                    'total_reviews' => 38,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(20)
                ],
                (object) [
                    'id' => 5,
                    'name' => 'Kelaniya Boys Hostel',
                    'price' => 20000,
                    'formatted_price' => 'LKR 20,000',
                    'type' => 'boys',
                    'type_display' => 'Boys Hostel',
                    'duration' => 'month',
                    'available_slots' => 0,
                    'capacity' => 45,
                    'image_url' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=300&fit=crop',
                    'location' => 'Kelaniya',
                    'university' => 'University of Kelaniya',
                    'description' => 'Budget-friendly hostel for Kelaniya University students.',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Common Kitchen', 'Laundry'],
                    'average_rating' => 4.1,
                    'total_reviews' => 33,
                    'availability_status' => 'Fully Booked',
                    'created_at' => now()->subDays(10)
                ]
            ],
            'girls' => [
                (object) [
                    'id' => 2,
                    'name' => 'University Girls Hostel - Block B',
                    'price' => 27000,
                    'formatted_price' => 'LKR 27,000',
                    'type' => 'girls',
                    'type_display' => 'Girls Hostel',
                    'duration' => 'month',
                    'available_slots' => 8,
                    'capacity' => 40,
                    'image_url' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=300&fit=crop',
                    'location' => 'Colombo',
                    'university' => 'University of Colombo',
                    'description' => 'Safe and comfortable girls hostel with 24/7 security.',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Cafeteria', 'Security', 'Gym', 'Library'],
                    'average_rating' => 4.7,
                    'total_reviews' => 62,
                    'availability_status' => 'Available',
                    'created_at' => now()->subDays(25)
                ],
                (object) [
                    'id' => 4,
                    'name' => 'NSBM Girls Hostel',
                    'price' => 24000,
                    'formatted_price' => 'LKR 24,000',
                    'type' => 'girls',
                    'type_display' => 'Girls Hostel',
                    'duration' => 'month',
                    'available_slots' => 5,
                    'capacity' => 35,
                    'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=400&h=300&fit=crop',
                    'location' => 'Pitipana',
                    'university' => 'NSBM',
                    'description' => 'Modern girls hostel with study-friendly environment.',
                    'facilities' => ['Wi-Fi', 'Study Room', 'Cafeteria', 'Library', 'Medical Room'],
                    'average_rating' => 4.6,
                    'total_reviews' => 29,
                    'availability_status' => 'Limited',
                    'created_at' => now()->subDays(15)
                ]
            ]
        ];

        return collect($hostels[$type] ?? []);
    }

    /**
     * Get sample reviews data
     */
    private function getSampleReviews()
    {
        return collect([
            (object) [
                'id' => 1,
                'rating' => 5,
                'comment' => 'Excellent hostel with great facilities. Highly recommended!',
                'user' => (object) [
                    'name' => 'Kasun Perera',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Kasun+Perera&background=007bff&color=fff'
                ],
                'hostelPackage' => (object) [
                    'name' => 'University Boys Hostel - Block A',
                    'type' => 'boys'
                ],
                'created_at' => now()->subDays(2),
                'formatted_date' => '2 days ago'
            ],
            (object) [
                'id' => 2,
                'rating' => 4,
                'comment' => 'Good location and clean rooms. Staff is very helpful.',
                'user' => (object) [
                    'name' => 'Nimali Silva',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Nimali+Silva&background=28a745&color=fff'
                ],
                'hostelPackage' => (object) [
                    'name' => 'University Girls Hostel - Block B',
                    'type' => 'girls'
                ],
                'created_at' => now()->subDays(5),
                'formatted_date' => '5 days ago'
            ],
            (object) [
                'id' => 3,
                'rating' => 5,
                'comment' => 'Amazing experience! Great community and facilities.',
                'user' => (object) [
                    'name' => 'Thilini Fernando',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Thilini+Fernando&background=dc3545&color=fff'
                ],
                'hostelPackage' => (object) [
                    'name' => 'NSBM Girls Hostel',
                    'type' => 'girls'
                ],
                'created_at' => now()->subDays(7),
                'formatted_date' => '1 week ago'
            ],
            (object) [
                'id' => 4,
                'rating' => 4,
                'comment' => 'Good value for money. Close to university campus.',
                'user' => (object) [
                    'name' => 'Sunil Wickramasinghe',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Sunil+Wickramasinghe&background=ffc107&color=000'
                ],
                'hostelPackage' => (object) [
                    'name' => 'SLIIT Boys Hostel',
                    'type' => 'boys'
                ],
                'created_at' => now()->subDays(10),
                'formatted_date' => '10 days ago'
            ],
            (object) [
                'id' => 5,
                'rating' => 3,
                'comment' => 'Decent hostel but could improve food quality.',
                'user' => (object) [
                    'name' => 'Priya Jayawardena',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Priya+Jayawardena&background=6f42c1&color=fff'
                ],
                'hostelPackage' => (object) [
                    'name' => 'Kelaniya Boys Hostel',
                    'type' => 'boys'
                ],
                'created_at' => now()->subDays(12),
                'formatted_date' => '12 days ago'
            ],
            (object) [
                'id' => 6,
                'rating' => 5,
                'comment' => 'Perfect place for studies. Very quiet and peaceful.',
                'user' => (object) [
                    'name' => 'Achini Rajapaksa',
                    'profile_image_url' => 'https://ui-avatars.com/api/?name=Achini+Rajapaksa&background=17a2b8&color=fff'
                ],
                'hostelPackage' => (object) [
                    'name' => 'University Girls Hostel - Block B',
                    'type' => 'girls'
                ],
                'created_at' => now()->subDays(15),
                'formatted_date' => '2 weeks ago'
            ]
        ]);
    }

    /**
     * Get statistics with error handling
     */
    private function getStats()
    {
        return [
            'total_students' => $this->getSafeCount('\App\Models\User', 1200),
            'total_hostels' => $this->getSafeCount('\App\Models\HostelPackage', 25),
            'total_bookings' => $this->getSafeCount('\App\Models\Booking', 950),
            'average_rating' => 4.5,
        ];
    }

    /**
     * Get sample stats for fallback
     */
    private function getSampleStats()
    {
        return [
            'established_year' => '2018',
            'total_students' => '1,200',
            'total_hostels' => '25',
            'success_rate' => '98.5%',
            'cities_covered' => 15,
            'universities_partnered' => 8,
            'total_bookings' => 950,
            'satisfaction_rate' => '96%'
        ];
    }

    /**
     * Safely get count from model with fallback
     */
    private function getSafeCount($modelClass, $fallback = 0)
    {
        try {
            if (class_exists($modelClass)) {
                return $modelClass::count();
            }
        } catch (\Exception $e) {
            \Log::warning("Error counting {$modelClass}: " . $e->getMessage());
        }
        
        return $fallback;
    }

    /**
     * Format price for display
     */
    private function formatPrice($price)
    {
        if (is_numeric($price)) {
            return 'LKR ' . number_format($price);
        }
        return $price;
    }

    /**
     * Ensure object has required properties with defaults
     */
    private function ensureHostelProperties($hostel)
    {
        $defaults = [
            'formatted_price' => $this->formatPrice($hostel->price ?? 0),
            'type_display' => ucfirst(($hostel->type ?? 'hostel') . ' hostel'),
            'duration' => 'month',
            'availability_status' => ($hostel->available_slots ?? 0) > 0 ? 'Available' : 'Fully Booked',
            'average_rating' => 0,
            'total_reviews' => 0,
            'facilities' => [],
            'description' => 'Student accommodation facility'
        ];

        foreach ($defaults as $property => $defaultValue) {
            if (!isset($hostel->$property)) {
                $hostel->$property = $defaultValue;
            }
        }

        return $hostel;
    }

    /**
     * Ensure review object has required properties
     */
    private function ensureReviewProperties($review)
    {
        if (!isset($review->formatted_date)) {
            $review->formatted_date = $review->created_at->diffForHumans();
        }

        if (!isset($review->user->profile_image_url)) {
            $review->user->profile_image_url = 'https://ui-avatars.com/api/?name=' . 
                urlencode($review->user->name) . '&background=007bff&color=fff';
        }

        return $review;
    }
}