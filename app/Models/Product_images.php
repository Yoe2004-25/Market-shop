<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product_images extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'image', 'primary'];

    protected $casts = [
        'primary' => 'boolean',   
    ];

    public function product()  
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function getImageUrlAttribute(): string  
    {
        return asset('storage/' . $this->image);
    }

    public function scopePrimary($query)
    {
        return $query->where('primary', true);
    }
}