<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'rental_item_id',
        'reported_by',
        'description',
        'photo_top',
        'photo_bottom',
        'photo_front',
        'photo_back',
        'photo_left',
        'photo_right',
        'status',
        'reviewed_by',
        'admin_feedback',
        'fine_amount',
        'reviewed_at',
        'user_confirmed',
        'user_confirmed_at',
        'fine_paid',
        'fine_paid_at',
        'fine_payment_method',
        'fine_transaction_id',
        'confirmed_by_kasir',
        'kasir_confirmed_at',
        'closed_by',
        'closed_at',
    ];

    protected $casts = [
        'fine_amount' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'user_confirmed' => 'boolean',
        'user_confirmed_at' => 'datetime',
        'fine_paid' => 'boolean',
        'fine_paid_at' => 'datetime',
        'kasir_confirmed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    /**
     * Get the rental item associated with this damage report
     */
    public function rentalItem(): BelongsTo
    {
        return $this->belongsTo(RentalItem::class);
    }

    /**
     * Get the user who reported the damage
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the admin who reviewed the damage
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get all photo paths as array
     */
    public function getPhotosAttribute(): array
    {
        return [
            'top' => $this->photo_top,
            'bottom' => $this->photo_bottom,
            'front' => $this->photo_front,
            'back' => $this->photo_back,
            'left' => $this->photo_left,
            'right' => $this->photo_right,
        ];
    }

    /**
     * Check if all photos are uploaded
     */
    public function hasAllPhotos(): bool
    {
        return $this->photo_top && $this->photo_bottom && 
               $this->photo_front && $this->photo_back && 
               $this->photo_left && $this->photo_right;
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'badge-pending',
            'reviewed' => 'badge-waiting',
            'resolved' => 'badge-done',
            default => 'badge-neutral',
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Review Admin',
            'reviewed' => 'Menunggu Konfirmasi User',
            'user_confirmed' => 'Menunggu Pembayaran Denda',
            'fine_paid' => 'Menunggu Konfirmasi Kasir',
            'kasir_confirmed' => 'Menunggu Admin Tutup Case',
            'resolved' => 'Selesai',
            default => 'Unknown',
        };
    }

    /**
     * Get kasir who confirmed the payment
     */
    public function kasirConfirmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by_kasir');
    }

    /**
     * Get admin who closed the case
     */
    public function closedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Get current step in the flow
     */
    public function getCurrentStep(): int
    {
        if ($this->status === 'resolved') return 6;
        if ($this->kasir_confirmed_at) return 5;
        if ($this->fine_paid) return 4;
        if ($this->user_confirmed) return 3;
        if ($this->reviewed_at) return 2;
        return 1;
    }
}
