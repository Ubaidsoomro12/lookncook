<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'description',
        'logo',     // ⭐ ADDED THIS SO LARAVEL WILL SAVE THE IMAGE PATH
        'bank_name',
        'account_title',
        'account_number',
        'iban',
        'deep_link',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getDisplayIconAttribute()
    {
        return $this->icon ?? match($this->type) {
            'bank'          => '🏦',
            'mobile_wallet' => '📱',
            'cod'           => '💵',
            default         => '💳',
        };
    }

    public function getTypeBadgeClass(): string
    {
        return match ($this->type) {
            'bank'          => 'bg-blue-50 text-blue-600 border-blue-200',
            'mobile_wallet' => 'bg-purple-50 text-purple-600 border-purple-200',
            'cod'           => 'bg-green-50 text-green-600 border-green-200',
            default         => 'bg-gray-50 text-gray-600 border-gray-200',
        };
    }

    public function getStatusBadgeClass(): string
    {
        return $this->is_active
            ? 'bg-green-50 text-green-600 border-green-200'
            : 'bg-red-50 text-red-600 border-red-200';
    }

    public function getDisplayAccountAttribute(): ?string
    {
        return $this->iban ?: $this->account_number;
    }
}