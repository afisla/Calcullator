<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Store extends Model
{
    protected $fillable = [
        'name', 'category', 'icon_emoji', 'pin',
        'is_open', 'description', 'sort_order', 'unit',
    ];

    protected $hidden = ['pin'];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function checkPin(string $pin): bool
    {
        return Hash::check($pin, $this->pin);
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->is_open ? 'Buka' : 'Tutup';
    }
}
