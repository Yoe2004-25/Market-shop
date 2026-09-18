<?php

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Products; 
use App\Models\User;  

class DeductProductStock
{
    /**
     * Create the event listener.
     */

    protected $product ; 
    protected $user ; 
    public function __construct(Products $product , User $user)
    {
        $this->product = $product ; 
        $this->user = $user ; 
    }

    /**
     * Handle the event.
     */
    public function handle(ProductCreated $event): void
    {
        $this->user->notify(new \App\Notifications\ProductCreated($this->product, $this->user));
    }
}