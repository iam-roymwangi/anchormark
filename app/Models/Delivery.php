<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'tracking_number',
        'courier_name', 'estimated_delivery_date',
        'actual_delivery_date', 'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

