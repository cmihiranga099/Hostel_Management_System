<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
       'user_id',
        'hostel_id',
        'hostel_package_id',  // Make sure this is included
        'booking_reference',
        'check_in_date',
        'check_out_date',
        'duration_days',
        'amount',
        'total_amount',
        'status',
        'booking_status',
        'payment_status',
        'payment_method',
        'payment_reference',
        'emergency_contact_name',
        'emergency_contact_phone',
        'special_requests',
        'student_id',
        'university',
        'booked_at',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'student_details' => 'array',
        'booked_at' => 'datetime',
    ];

    // Boot method to generate booking reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'UHM-' . strtoupper(Str::random(8));
            }
        });
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the user that owns the booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hostel for this booking
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    /**
     * Get the hostel package for this booking
     */
    public function hostelPackage()
    {
        return $this->belongsTo(HostelPackage::class);
    }

    /**
     * Get the package for this booking (alias)
     */
    public function package()
    {
        return $this->belongsTo(HostelPackage::class, 'hostel_package_id');
    }

    /**
     * Get the payment for this booking
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get all payments for this booking
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get reviews for this booking
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        $amount = $this->total_amount ?? $this->amount ?? 0;
        return 'LKR ' . number_format($amount, 2);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $status = $this->booking_status ?? $this->status ?? 'pending';
        
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'confirmed' => '<span class="badge bg-success">Confirmed</span>',
            'cancelled' => '<span class="badge bg-danger">Cancelled</span>',
            'completed' => '<span class="badge bg-info">Completed</span>',
            'checked_in' => '<span class="badge bg-primary">Checked In</span>',
            'checked_out' => '<span class="badge bg-secondary">Checked Out</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get payment status badge HTML
     */
    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Payment Pending</span>',
            'paid' => '<span class="badge bg-success">Paid</span>',
            'completed' => '<span class="badge bg-success">Paid</span>',
            'failed' => '<span class="badge bg-danger">Payment Failed</span>',
            'refunded' => '<span class="badge bg-info">Refunded</span>',
            'partial' => '<span class="badge bg-warning">Partially Paid</span>',
        ];

        return $badges[$this->payment_status ?? 'pending'] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get booking duration in days
     */
    public function getDurationAttribute()
    {
        if ($this->duration_days) {
            return $this->duration_days;
        }
        
        if ($this->check_in_date && $this->check_out_date) {
            return $this->check_in_date->diffInDays($this->check_out_date);
        }
        
        return 0;
    }

    /**
     * Get formatted dates
     */
    public function getFormattedDatesAttribute()
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 'Dates not set';
        }
        
        return $this->check_in_date->format('M d, Y') . ' - ' . $this->check_out_date->format('M d, Y');
    }

    /**
     * Get booking status for display
     */
    public function getDisplayStatusAttribute()
    {
        return ucfirst($this->booking_status ?? $this->status ?? 'pending');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'pending')
              ->orWhere('booking_status', 'pending');
        });
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'confirmed')
              ->orWhere('booking_status', 'confirmed');
        });
    }

    /**
     * Scope for paid bookings
     */
    public function scopePaid($query)
    {
        return $query->whereIn('payment_status', ['paid', 'completed']);
    }

    /**
     * Scope for unpaid bookings
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for active bookings
     */
    public function scopeActive($query)
    {
        return $query->whereIn('booking_status', ['confirmed', 'checked_in'])
                    ->orWhereIn('status', ['confirmed', 'checked_in']);
    }

    /**
     * Scope for user's bookings
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Confirm the booking
     */
    public function confirm()
    {
        $this->update([
            'status' => 'confirmed',
            'booking_status' => 'confirmed'
        ]);
        
        return $this;
    }

    /**
     * Cancel the booking
     */
    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'booking_status' => 'cancelled'
        ]);
        
        // Increase available slots back if hostel/package exists
        if ($this->hostelPackage && method_exists($this->hostelPackage, 'increaseAvailableSlots')) {
            $this->hostelPackage->increaseAvailableSlots();
        }
        
        return $this;
    }

    /**
     * Mark booking as paid
     */
    public function markAsPaid($paymentMethod = null, $paymentReference = null)
    {
        $updateData = ['payment_status' => 'paid'];
        
        if ($paymentMethod) {
            $updateData['payment_method'] = $paymentMethod;
        }
        
        if ($paymentReference) {
            $updateData['payment_reference'] = $paymentReference;
        }
        
        $this->update($updateData);
        
        return $this;
    }

    /**
     * Check if booking can be paid
     */
    public function canBePaid()
    {
        $status = $this->booking_status ?? $this->status;
        return in_array($status, ['pending', 'confirmed']) && 
               $this->payment_status === 'pending';
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled()
    {
        $status = $this->booking_status ?? $this->status;
        
        if (!in_array($status, ['pending', 'confirmed'])) {
            return false;
        }
        
        if ($this->check_in_date && $this->check_in_date->isPast()) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if booking can be modified
     */
    public function canBeModified()
    {
        $status = $this->booking_status ?? $this->status;
        
        return $status === 'pending' && 
               $this->check_in_date && 
               $this->check_in_date->isFuture();
    }

    /**
     * Get total paid amount
     */
    public function getTotalPaidAmount()
    {
        return $this->payments()
                    ->whereIn('status', ['completed', 'paid'])
                    ->sum('amount');
    }

    /**
     * Get remaining amount to be paid
     */
    public function getRemainingAmount()
    {
        $totalAmount = $this->total_amount ?? $this->amount ?? 0;
        $paidAmount = $this->getTotalPaidAmount();
        
        return max(0, $totalAmount - $paidAmount);
    }

    /**
     * Check if booking is fully paid
     */
    public function isFullyPaid()
    {
        return $this->getRemainingAmount() <= 0;
    }

    /**
     * Generate invoice number
     */
    public function getInvoiceNumber()
    {
        return 'INV-' . $this->booking_reference;
    }
}