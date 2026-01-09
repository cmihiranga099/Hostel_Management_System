<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\HostelPackage;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Remove the constructor completely for now
    
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        // Get user's bookings (with safe fallback)
        $bookings = collect(); // Empty collection as fallback
        if (class_exists('App\Models\Booking')) {
            try {
                $bookings = $user->bookings()
                    ->with('hostelPackage')
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                $bookings = collect();
            }
        }

        // Get user's reviews (with safe fallback)
        $reviews = collect(); // Empty collection as fallback
        if (class_exists('App\Models\Review')) {
            try {
                $reviews = $user->reviews()
                    ->with('hostelPackage')
                    ->latest()
                    ->take(5)
                    ->get();
            } catch (\Exception $e) {
                $reviews = collect();
            }
        }

        // Get user statistics (with safe fallback)
        $stats = [
            'total_bookings' => 0,
            'active_bookings' => 0,
            'total_payments' => 0,
            'total_reviews' => 0,
        ];

        try {
            if (class_exists('App\Models\Booking')) {
                $stats['total_bookings'] = $user->bookings()->count();
                $stats['active_bookings'] = $user->bookings()->where('status', 'confirmed')->count();
                $stats['total_payments'] = $user->bookings()->where('payment_status', 'paid')->sum('amount');
            }
            if (class_exists('App\Models\Review')) {
                $stats['total_reviews'] = $user->reviews()->count();
            }
        } catch (\Exception $e) {
            // Keep default stats if there's an error
        }

        // Get available hostels (with safe fallback)
        $availableHostels = collect(); // Empty collection as fallback
        if (class_exists('App\Models\HostelPackage')) {
            try {
                $availableHostels = HostelPackage::where('is_active', true)
                    ->where('available_slots', '>', 0)
                    ->take(4)
                    ->get();
            } catch (\Exception $e) {
                $availableHostels = collect();
            }
        }

        return view('dashboard.index', compact('bookings', 'reviews', 'stats', 'availableHostels'));
    }

    public function bookings()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        $bookings = collect(); // Empty collection as fallback
        
        try {
            if (class_exists('App\Models\Booking')) {
                $bookings = $user->bookings()
                    ->with(['hostelPackage'])
                    ->latest()
                    ->paginate(10);
            }
        } catch (\Exception $e) {
            $bookings = collect();
        }

        return view('dashboard.bookings', compact('bookings'));
    }

    public function reviews()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        $reviews = collect(); // Empty collection as fallback
        
        try {
            if (class_exists('App\Models\Review')) {
                $reviews = $user->reviews()
                    ->with('hostelPackage')
                    ->latest()
                    ->paginate(10);
            }
        } catch (\Exception $e) {
            $reviews = collect();
        }

        return view('dashboard.reviews', compact('reviews'));
    }

    public function profile()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'nic' => 'nullable|string|max:12',
            'university' => 'nullable|string|max:255',
            'faculty' => 'nullable|string|max:255',
            'student_id' => 'nullable|string|max:50',
            'year_of_study' => 'nullable|integer|min:1|max:6',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:15',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['profile_image']);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            try {
                // Delete old image if exists
                if ($user->profile_image) {
                    \Storage::disk('public')->delete($user->profile_image);
                }
                
                $imagePath = $request->file('profile_image')->store('profile-images', 'public');
                $data['profile_image'] = $imagePath;
            } catch (\Exception $e) {
                // Handle upload error gracefully
            }
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}