<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Orders; 
class Payments extends Model
{
    /** @use HasFactory<\Database\Factories\PaymentsFactory> */
    use HasFactory;

    use SoftDeletes ; 

    protected $fillable =[
        'order_id' , 
        'payment_method', 
        'transaction',
        'status', 
        'amount', 
    ];

    protected $cats =[
            'amount' => 'decimal:2',
    ];
    public function orders() 
    {
        return $this->belongsTo(Orders::class,'order_id'); 
    }
}