<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'amount',
        'currency',
        'payment_method',
        'payment_status',
        'status',
        'payment_reference',
        'transaction_id',
    // ...existing code...
        'payment_method_id',
        'gateway_response',
    // ...existing code...
        'authorization_code',
        'card_last_four',
        'card_type',
        'processed_at',
        'paid_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    // ...existing code...
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // Boot method to generate payment reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_reference)) {
                $payment->payment_reference = 'PAY-' . strtoupper(Str::random(8));
            }
            if (empty($payment->transaction_id)) {
                $payment->transaction_id = 'TXN-' . strtoupper(uniqid());
            }
        });
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the user that owns the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the booking for this payment
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'LKR ' . number_format($this->amount, 2);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $status = $this->payment_status ?? $this->status ?? 'pending';
        
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'processing' => '<span class="badge bg-info">Processing</span>',
            'completed' => '<span class="badge bg-success">Completed</span>',
            'succeeded' => '<span class="badge bg-success">Succeeded</span>',
            'paid' => '<span class="badge bg-success">Paid</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
            'refunded' => '<span class="badge bg-info">Refunded</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get display status
     */
    public function getDisplayStatusAttribute()
    {
        return ucfirst($this->payment_status ?? $this->status ?? 'pending');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope for successful payments
     */
    public function scopeSucceeded($query)
    {
        return $query->whereIn('status', ['succeeded', 'completed', 'paid'])
                    ->orWhereIn('payment_status', ['succeeded', 'completed', 'paid']);
    }

    /**
     * Scope for failed payments
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed')
                    ->orWhere('payment_status', 'failed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                    ->orWhere('payment_status', 'pending');
    }

    /**
     * Scope for user's payments
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ==========================================
    // METHODS
    // ==========================================

    /**
     * Mark payment as succeeded
     */
    public function markAsSucceeded($gatewayResponse = null)
    {
        $updateData = [
            'status' => 'completed',
            'payment_status' => 'completed',
            'processed_at' => now(),
            'paid_at' => now(),
        ];

        if ($gatewayResponse) {
            $updateData['gateway_response'] = $gatewayResponse;
        }

        $this->update($updateData);
        
        // Also update the booking
        if ($this->booking) {
            $this->booking->update([
                'payment_status' => 'paid',
                'booking_status' => 'confirmed'
            ]);
        }

        return $this;
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed($gatewayResponse = null)
    {
        $updateData = [
            'status' => 'failed',
            'payment_status' => 'failed',
            'processed_at' => now(),
        ];

        if ($gatewayResponse) {
            $updateData['gateway_response'] = $gatewayResponse;
        }

        $this->update($updateData);

        return $this;
    }

    /**
     * Mark payment as processing
     */
    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'payment_status' => 'processing'
        ]);

        return $this;
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful()
    {
        $status = $this->payment_status ?? $this->status;
        return in_array($status, ['completed', 'succeeded', 'paid']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        $status = $this->payment_status ?? $this->status;
        return $status === 'pending';
    }

    /**
     * Check if payment failed
     */
    public function isFailed()
    {
        $status = $this->payment_status ?? $this->status;
        return $status === 'failed';
    }

    /**
     * Get masked card number
     */
    public function getMaskedCardNumber()
    {
        if ($this->card_last_four) {
            return '****' . $this->card_last_four;
        }
        
        // Try to extract from gateway response
    $response = $this->gateway_response ?? [];
        if (isset($response['card_last_four'])) {
            return '****' . $response['card_last_four'];
        }
        
        return '****0000';
    }

    /**
     * Generate receipt data
     */
    public function getReceiptData()
    {
        return [
            'payment_id' => $this->id,
            'payment_reference' => $this->payment_reference,
            'transaction_id' => $this->transaction_id,
            'amount' => $this->amount,
            'currency' => $this->currency ?? 'LKR',
            'payment_method' => ucfirst($this->payment_method),
            'status' => $this->display_status,
            'processed_at' => $this->processed_at,
            'booking_reference' => $this->booking->booking_reference ?? 'N/A',
            'hostel_name' => $this->booking->hostel->name ?? 'N/A'
        ];
    }
}