<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Payments; 
use App\Models\OrdersItems; 
use App\Models\Coupons ; 

class Orders extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id', 'coupon_id',
        'status', 'payment_status',
        'total', 'shipping_cost',   
        'tax', 'grand_total',
    ];

    protected $casts = [
        'total'         => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'tax'           => 'decimal:2',
        'grand_total'   => 'decimal:2',
    ];

  

    public function user()  
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()  
    {
        return $this->belongsTo(Coupons::class, 'coupon_id');
    }

    public function items()  
    {
        return $this->hasMany(OrdersItems::class, 'order_id');
    }

    public function orderItems()  
    {
        return $this->items();
    }

    public function payment()   
    {
        return $this->hasOne(Payments::class, 'order_id');
    }
}