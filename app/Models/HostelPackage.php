<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'description',
        'price',
        'duration',
        'capacity',
        'available_slots',
        'facilities',
        'rules',
        'image',
        'is_active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'rules' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-hostel.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return 'LKR ' . number_format($this->price, 2);
    }

    public function getAvailabilityStatusAttribute()
    {
        if ($this->available_slots > 0) {
            return 'Available';
        }
        return 'Full';
    }

    public function getTypeDisplayAttribute()
    {
        return ucfirst($this->type) . "'s Hostel";
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

    public function scopeBoysHostel($query)
    {
        return $query->where('type', 'boys');
    }

    public function scopeGirlsHostel($query)
    {
        return $query->where('type', 'girls');
    }

    // Methods
    public function decreaseAvailableSlots($count = 1)
    {
        $this->decrement('available_slots', $count);
    }

    public function increaseAvailableSlots($count = 1)
    {
        $this->increment('available_slots', $count);
    }

    public function getAverageRating()
    {
        return $this->reviews()->where('is_approved', true)->avg('rating') ?? 0;
    }

    public function getTotalReviews()
    {
        return $this->reviews()->where('is_approved', true)->count();
    }
}