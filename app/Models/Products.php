<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',   
        'brand_id',
        'name', 'slug', 'description',
        'price', 'discount', 'stock',
        'sku', 'image', 'status',
    ];

    protected $casts = [
        'price'    => 'decimal:2',
        'discount' => 'decimal:2',
        'stock'    => 'integer',
    ];

   

    public function category()  
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function brand()     
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }

    public function reviews()   
    {
        return $this->hasMany(Reviews::class, 'product_id');
    }

    public function images()    
    {
        return $this->hasMany(Product_images::class, 'product_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(Product_images::class, 'product_id')
            ->where('primary', true);
    }

    public function cartItems()  
    {
        return $this->hasMany(Cart_items::class, 'product_id');
    }

    public function orderItems() 
    {
        return $this->hasMany(OrdersItems::class, 'product_id');
    }

   

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

  

    public function getFinalPriceAttribute(): float
    {
        return $this->price - ($this->discount ?? 0);
    }
}