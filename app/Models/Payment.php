<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'midtrans_order_id', 'snap_token',
        'status', 'amount', 'payment_method', 'payment_type',
        'raw_response', 'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'paid'    => 'Lunas',
            'failed'  => 'Gagal',
            'expired' => 'Kedaluwarsa',
            default   => '-',
        };
    }
}
