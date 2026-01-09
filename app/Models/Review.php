<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hostel_package_id',
        'rating',
        'comment',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hostelPackage()
    {
        return $this->belongsTo(HostelPackage::class);
    }

    // Accessors
    public function getStarRatingAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('M d, Y') : 'Unknown Date';
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at ? $this->created_at->diffForHumans() : 'Unknown time';
    }

    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => 'Poor',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Excellent'
        ];

        return $ratings[$this->rating] ?? 'Unknown';
    }

    public function getStatusBadgeAttribute()
    {
        if ($this->is_approved) {
            return '<span class="badge bg-success">Approved</span>';
        } else {
            return '<span class="badge bg-warning">Pending</span>';
        }
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeForHostel($query, $hostelId)
    {
        return $query->where('hostel_package_id', $hostelId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function approve()
    {
        $this->update(['is_approved' => true]);
        return $this;
    }

    public function disapprove()
    {
        $this->update(['is_approved' => false]);
        return $this;
    }

    public function reject()
    {
        $this->delete();
        return $this;
    }

    public function canBeEditedBy($user)
    {
        return $this->user_id === $user->id && !$this->is_approved;
    }

    public function canBeDeletedBy($user)
    {
        return $this->user_id === $user->id || ($user->hasRole && $user->hasRole('admin'));
    }

    // Static methods
    public static function getAverageRatingForHostel($hostelId)
    {
        return self::approved()
            ->where('hostel_package_id', $hostelId)
            ->avg('rating') ?? 0;
    }

    public static function getTotalReviewsForHostel($hostelId)
    {
        return self::approved()
            ->where('hostel_package_id', $hostelId)
            ->count();
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if (!isset($review->is_approved)) {
                $review->is_approved = false;
            }
        });
    }
}