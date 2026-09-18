<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Products ; 
use App\Models\User; 

class Reviews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'product_id', 'rating', 'comment'];

    protected $casts = [
        'rating' => 'integer',
    ];

    public const MIN_RATING = 1;
    public const MAX_RATING = 5;

    public function product()  
    {
        return $this->belongsTo(Products::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePositive($q)
    {
        return $q->where('rating', '>=', 4);
    }

    public function scopeNegative($q)
    {
        return $q->where('rating', '<=', 2);
    }

    public function isPositive(): bool 
    {
        return $this->rating >= 4;
    }

    public function isNegative(): bool
    {
        return $this->rating <= 2;
    }
}