<?php

namespace App\Observers;

use App\Models\Reviews;

class ReviewsObserver
{
    /**
     * Handle the Reviews "created" event.
     */
    public function created(Reviews $reviews): void
    {
        //
    }

    /**
     * Handle the Reviews "updated" event.
     */
    public function updated(Reviews $reviews): void
    {
        //
    }

    /**
     * Handle the Reviews "deleted" event.
     */
    public function deleted(Reviews $reviews): void
    {
        //
    }

    /**
     * Handle the Reviews "restored" event.
     */
    public function restored(Reviews $reviews): void
    {
        //
    }

    /**
     * Handle the Reviews "force deleted" event.
     */
    public function forceDeleted(Reviews $reviews): void
    {
        //
    }
}
