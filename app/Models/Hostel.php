<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'location',
        'city',
        'address',
        'type',
        'capacity',
        'available_slots',
        'price',
        'monthly_price',
        'facilities',
        'rules',
        'image_url',
        'images',
        'is_active',
        'is_featured',
        'contact_phone',
        'contact_email',
        'latitude',
        'longitude',
        'check_in_time',
        'check_out_time',
        'cancellation_policy',
        'terms_and_conditions',
        'average_rating',
        'total_reviews'
    ];

    protected $casts = [
        'facilities' => 'array',
        'rules' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'monthly_price' => 'decimal:2',
        'latitude' => 'decimal:8,6',
        'longitude' => 'decimal:9,6',
        'average_rating' => 'decimal:2,1',
        'total_reviews' => 'integer'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // REMOVED: reviews relationship to prevent SQL errors
    /*
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    */

    public function packages()
    {
        return $this->hasMany(HostelPackage::class);
    }

    // Accessors
    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type) . ' Hostel';
    }

    public function getFormattedPriceAttribute()
    {
        return 'LKR ' . number_format($this->price, 2);
    }

    public function getDurationAttribute()
    {
        return 'month'; // Default duration
    }

    public function getImageUrlAttribute($value)
    {
        return $value ?: asset('images/default-hostel.jpg');
    }

    public function getAvailabilityStatusAttribute()
    {
        if ($this->available_slots > 0) {
            return 'Available';
        }
        return 'Fully Booked';
    }

    // Methods
    public function getAverageRating()
    {
        // Return static rating from database field instead of calculating from reviews
        return $this->average_rating ?: 4.0;
    }

    public function getTotalReviews()
    {
        // Return static count from database field instead of counting reviews
        return $this->total_reviews ?: 0;
    }

    public function isAvailable()
    {
        return $this->is_active && $this->available_slots > 0;
    }

    public function hasSlots($requiredSlots = 1)
    {
        return $this->available_slots >= $requiredSlots;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_slots', '>', 0);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeInPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // REMOVED: withReviews scope to prevent SQL errors
    /*
    public function scopeWithReviews($query)
    {
        return $query->with(['reviews' => function($q) {
            $q->with('user')->latest();
        }]);
    }
    */
}