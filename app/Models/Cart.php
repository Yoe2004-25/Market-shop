<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User; 
use App\Models\Cart_items; 

class Cart extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'content', 'user_id'];

    public function user()  
    {
        return $this->belongsTo(User::class);
    }

    public function items()  
    {
        return $this->hasMany(Cart_items::class, 'cart_id');
    }

    public function cartItems()   
    {
        return $this->items();
    }

   

    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn ($item) => $item->price * $item->quantity);
    }
}