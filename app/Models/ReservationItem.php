<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    protected $fillable = ['reservation_id', 'product_id', 'product_name', 'unit_price', 'quantity', 'total_price'];
    
    public function reservation() { return $this->belongsTo(Reservation::class); }
}