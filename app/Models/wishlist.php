<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Products; 
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes; 

class wishlist extends Model
{
    use HasFactory;

    use SoftDeletes ; 
    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
}