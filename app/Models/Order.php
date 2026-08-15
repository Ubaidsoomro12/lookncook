<?php
// FILE: app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'order_number', 'total_amount',
        'payment_method_slug', 'payment_method_id', 'payment_status',
        'customer_name', 'customer_phone', 'customer_email',
        'city', 'delivery_address',
        'bank_name', 'account_title', 'account_number', 'transaction_reference',
        'payment_screenshot',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}