<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User ; 
use App\Models\Products; 
use App\Models\Orders; 

class OrdersItems extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'details', 'order_id', 'user_id', 'product_id',
        'price', 'quantity', 'subtotal',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order()   
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function user()   
    {
        return $this->belongsTo(User::class);
    }

    public function product()   
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}