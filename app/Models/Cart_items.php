<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Cart;
use App\Models\Products; 
class Cart_items extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['cart_id', 'product_id', 'quantity', 'price'];

    protected $casts = [
        'price'    => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function cart()  
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()  
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

   

    public function getSubtotalAttribute(): float
    {
        return $this->price * $this->quantity;
    }
}