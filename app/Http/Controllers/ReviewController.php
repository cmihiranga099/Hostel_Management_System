<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // Complete reviews data with enhanced properties
        $reviews = collect([
            (object)[
                'id' => 1,
                'user_name' => 'Kasun Perera',
                'hostel_name' => 'University Boys Hostel - Colombo',
                'hostel_type' => 'boys',
                'rating' => 5,
                'comment' => 'Excellent facilities and very clean environment. The staff is friendly and helpful. Internet connectivity is great for online studies. The food quality is amazing and the study rooms are well-equipped with all necessary amenities.',
                'created_at' => '2024-12-15',
                'profile_image' => 'https://ui-avatars.com/api/?name=Kasun+Perera&background=4a90e2&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1555854877-bab0e460b1e5?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 2,
                'user_name' => 'Nimali Silva',
                'hostel_name' => 'Queens Girls Hostel - Kandy',
                'hostel_type' => 'girls',
                'rating' => 4,
                'comment' => 'Good hostel with decent facilities. The food is tasty and the location is convenient for university. Security is top-notch and the wardens are very caring. Great environment for studying.',
                'created_at' => '2024-12-10',
                'profile_image' => 'https://ui-avatars.com/api/?name=Nimali+Silva&background=ff8c42&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 3,
                'user_name' => 'Chaminda Rathnayake',
                'hostel_name' => 'Central Boys Hostel - Galle',
                'hostel_type' => 'boys',
                'rating' => 5,
                'comment' => 'Amazing experience! Clean rooms, good food, and excellent study environment. The WiFi is fast and reliable. Highly recommended for any student looking for quality accommodation.',
                'created_at' => '2024-12-08',
                'profile_image' => 'https://ui-avatars.com/api/?name=Chaminda+Rathnayake&background=2c5282&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 4,
                'user_name' => 'Sanduni Fernando',
                'hostel_name' => 'Sunshine Girls Hostel - Colombo',
                'hostel_type' => 'girls',
                'rating' => 4,
                'comment' => 'Nice and clean hostel. The rooms are spacious and well-maintained. Staff is cooperative and the food quality is good. Perfect place for female students with great safety measures.',
                'created_at' => '2024-12-05',
                'profile_image' => 'https://ui-avatars.com/api/?name=Sanduni+Fernando&background=e67e22&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 5,
                'user_name' => 'Tharindu Wickramasinghe',
                'hostel_name' => 'Modern Boys Hostel - Kelaniya',
                'hostel_type' => 'boys',
                'rating' => 5,
                'comment' => 'Outstanding hostel with modern facilities. AC rooms, hot water, and excellent internet. The management is very professional and responsive to student needs. Best value for money!',
                'created_at' => '2024-12-02',
                'profile_image' => 'https://ui-avatars.com/api/?name=Tharindu+Wickramasinghe&background=87ceeb&color=333&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 6,
                'user_name' => 'Priyanka Jayawardena',
                'hostel_name' => 'Royal Girls Hostel - Peradeniya',
                'hostel_type' => 'girls',
                'rating' => 4,
                'comment' => 'Very good hostel with beautiful surroundings. The food is delicious and the rooms are comfortable. Great place to stay during university with excellent recreational facilities.',
                'created_at' => '2024-11-28',
                'profile_image' => 'https://ui-avatars.com/api/?name=Priyanka+Jayawardena&background=ffb347&color=333&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 7,
                'user_name' => 'Nuwan Kumara',
                'hostel_name' => 'Elite Boys Hostel - Ruhuna',
                'hostel_type' => 'boys',
                'rating' => 3,
                'comment' => 'Decent hostel for the price. Basic facilities are available but could be improved. Good for students on a budget who need affordable accommodation near the university.',
                'created_at' => '2024-11-25',
                'profile_image' => 'https://ui-avatars.com/api/?name=Nuwan+Kumara&background=34495e&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 8,
                'user_name' => 'Hasini Perera',
                'hostel_name' => 'Paradise Girls Hostel - Jaffna',
                'hostel_type' => 'girls',
                'rating' => 5,
                'comment' => 'Excellent hostel! Very clean, safe, and comfortable. The staff treats us like family. Best decision I made for my university accommodation. Highly recommend to all female students!',
                'created_at' => '2024-11-20',
                'profile_image' => 'https://ui-avatars.com/api/?name=Hasini+Perera&background=e74c3c&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 9,
                'user_name' => 'Ravindu Senanayake',
                'hostel_name' => 'Metropolitan Boys Hostel - Nugegoda',
                'hostel_type' => 'boys',
                'rating' => 4,
                'comment' => 'Great location with easy access to university and public transport. Rooms are well-maintained and the common areas are spacious. The laundry facilities are convenient.',
                'created_at' => '2024-11-18',
                'profile_image' => 'https://ui-avatars.com/api/?name=Ravindu+Senanayake&background=9b59b6&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 10,
                'user_name' => 'Ishara Kumari',
                'hostel_name' => 'Garden View Girls Hostel - Maharagama',
                'hostel_type' => 'girls',
                'rating' => 5,
                'comment' => 'Beautiful hostel with garden views from every room. The environment is peaceful and perfect for studies. Excellent security and very supportive management team.',
                'created_at' => '2024-11-15',
                'profile_image' => 'https://ui-avatars.com/api/?name=Ishara+Kumari&background=1abc9c&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c35a?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 11,
                'user_name' => 'Dilshan Mendis',
                'hostel_name' => 'Capital Boys Hostel - Colombo',
                'hostel_type' => 'boys',
                'rating' => 4,
                'comment' => 'Good value for money. The rooms are clean and the food is decent. WiFi works well most of the time. Staff is helpful and responsive to complaints.',
                'created_at' => '2024-11-12',
                'profile_image' => 'https://ui-avatars.com/api/?name=Dilshan+Mendis&background=3498db&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1560448075-cbc16bb4af8e?w=100&h=100&fit=crop&crop=center'
            ],
            (object)[
                'id' => 12,
                'user_name' => 'Malsha Wijesinghe',
                'hostel_name' => 'Rose Garden Girls Hostel - Kandy',
                'hostel_type' => 'girls',
                'rating' => 5,
                'comment' => 'Absolutely love this place! The environment is so peaceful and conducive for studying. The wardens are like mothers to us. Highly recommended for girls studying in Kandy.',
                'created_at' => '2024-11-08',
                'profile_image' => 'https://ui-avatars.com/api/?name=Malsha+Wijesinghe&background=e91e63&color=fff&size=128',
                'hostel_image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=100&h=100&fit=crop&crop=center'
            ]
        ]);

        // Add additional properties to each review
        $reviews = $reviews->map(function($review) {
            $review->star_rating = $this->generateStarRating($review->rating);
            $review->rating_text = $this->getRatingText($review->rating);
            $review->formatted_date = date('M d, Y', strtotime($review->created_at));
            $review->time_ago = $this->getTimeAgo($review->created_at);
            $review->type_display = ucfirst($review->hostel_type) . ' Hostel';
            $review->is_recent = strtotime($review->created_at) > strtotime('-7 days');
            return $review;
        });

        // IMPORTANT: Change this line to use the correct view path
        return view('home.reviews', compact('reviews'));
        //          ^^^^ Add 'home.' prefix to match your directory structure
    }

    /**
     * Generate HTML star rating based on numeric rating
     */
    private function generateStarRating($rating)
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    /**
     * Get text representation of rating
     */
    private function getRatingText($rating)
    {
        $ratings = [
            1 => 'Poor',
            2 => 'Fair', 
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Excellent'
        ];
        return $ratings[$rating] ?? 'Unknown';
    }

    /**
     * Calculate time difference in human readable format
     */
    private function getTimeAgo($date)
    {
        $time = time() - strtotime($date);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        
        return floor($time/31536000) . ' years ago';
    }

    // Keep all your other existing methods unchanged...
    public function create($hostelId = null)
    {
        return redirect()->route('reviews')->with('info', 'Review submission feature coming soon!');
    }

    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Review submission is currently disabled.'
        ], 403);
    }

    public function show($id)
    {
        return redirect()->route('reviews');
    }

    public function edit($id)
    {
        return redirect()->route('reviews');
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('reviews');
    }

    public function destroy($id)
    {
        return redirect()->route('reviews');
    }

    public function myReviews()
    {
        return redirect()->route('reviews');
    }

    public function hostelReviews($hostel)
    {
        return redirect()->route('reviews');
    }
}