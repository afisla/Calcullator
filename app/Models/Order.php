<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'store_id', 'session_token', 'order_code', 'queue_number', 'queue_code',
        'customer_name', 'customer_class', 'customer_phone',
        'status', 'payment_status', 'total_price', 'rejection_reason', 'paid_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'total_price'  => 'decimal:2',
        'queue_number' => 'integer',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // === Status Helpers ===

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Belum Diproses',
            'processing' => 'Diproses',
            'ready'      => 'Siap Ambil',
            'completed'  => 'Selesai',
            'rejected'   => 'Ditolak',
            default      => 'Tidak Diketahui',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending'    => 'gray',
            'paid'       => 'blue',
            'processing' => 'yellow',
            'ready'      => 'green',
            'completed'  => 'purple',
            'rejected'   => 'red',
            default      => 'gray',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'pending'    => '⏳',
            'paid'       => '💳',
            'processing' => '🍳',
            'ready'      => '🎉',
            'completed'  => '✅',
            'rejected'   => '❌',
            default      => '❓',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending'  => 'Menunggu Pembayaran',
            'paid'     => 'Lunas',
            'failed'   => 'Gagal',
            'expired'  => 'Kedaluwarsa',
            default    => '-',
        };
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    // === Static Helpers ===

    /**
     * Generate kode pesanan unik
     */
    public static function generateCode(): string
    {
        do {
            $code = 'ORD-' . date('ymd') . '-' . strtoupper(Str::random(4));
        } while (static::where('order_code', $code)->exists());

        return $code;
    }

    /**
     * Generate nomor antrian format KOP-001 atau KTN-001
     */
    public static function generateQueueCode(Store $store): array
    {
        $prefix = $store->unit === 'koperasi' ? 'KOP' : 'KTN';

        $count = static::where('store_id', $store->id)
            ->whereDate('created_at', today())
            ->count() + 1;

        $queueCode   = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        $queueNumber = $count;

        return [$queueNumber, $queueCode];
    }
}
