<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'handled_by',
        'start_at',
        'due_at',
        'returned_at',
        'status',
        'subtotal',
        'discount',
        'total',
        'paid',
        'notes',
        'kode', // kode transaksi unik
        'fine',
        'fine_paid',
        'late_fee',
        'late_hours',
        'late_fee_description',
        'cancelled_at',
        'cancel_reason',
        // Delivery fields
        'delivered_at',
        'delivery_confirmed_at',
        'delivered_by',
        'delivery_notes',
        'delivery_address',
        'delivery_method',
        'shipping_cost',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'delivered_at' => 'datetime',
        'delivery_confirmed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Relasi ke user yang mengantarkan barang (kurir/kasir/admin)
     */
    public function deliverer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RentalItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Cek apakah rental sedang menunggu pengantaran
     */
    public function isAwaitingDelivery(): bool
    {
        return $this->status === 'menunggu_pengantaran';
    }

    /**
     * Cek apakah barang sudah diantar tapi belum dikonfirmasi user
     */
    public function isAwaitingDeliveryConfirmation(): bool
    {
        return $this->status === 'menunggu_pengantaran' 
            && $this->delivered_at !== null 
            && $this->delivery_confirmed_at === null;
    }

    /**
     * Cek apakah barang belum diantar
     */
    public function isPendingDelivery(): bool
    {
        return $this->status === 'menunggu_pengantaran' 
            && $this->delivered_at === null;
    }

    /**
     * Dapatkan waktu mulai efektif (setelah konfirmasi penerimaan)
     */
    public function getEffectiveStartTime(): ?Carbon
    {
        return $this->delivery_confirmed_at ?? $this->start_at;
    }

    /**
     * Generate kode transaksi unik format RNT-XXX (sesuai diagram UC006)
     * Dengan database lock untuk mencegah race condition
     */
    public static function generateKodeUnik(): string
    {
        return \DB::transaction(function() {
            $maxTry = 100;
            
            for ($i = 0; $i < $maxTry; $i++) {
                // Format: RNT-XXX (3 digit angka)
                $angka = str_pad(strval(rand(1, 999)), 3, '0', STR_PAD_LEFT);
                $kode = 'RNT-' . $angka;
                
                // Lock table untuk prevent race condition
                if (!self::where('kode', $kode)->lockForUpdate()->exists()) {
                    return $kode;
                }
            }
            
            // Fallback: RNT dengan timestamp untuk uniqueness
            return 'RNT-' . strtoupper(substr(md5(microtime(true) . rand()), 0, 5));
        });
    }
}


