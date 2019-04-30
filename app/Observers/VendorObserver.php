<?php

namespace App\Observers;

use App\Vendor;
use App\Notifiable\AdminNotifiable;
use App\Notifications\VendorCreatedNotification;

class VendorObserver
{
    /**
     * Handle the Vendor "created" event.
     *
     * @param  \App\Vendor  $vendor
     * @return void
     */
    public function created(Vendor $vendor)
    {
        if ($vendor->status == 'unapproved') {
            (new AdminNotifiable)->notify(new VendorCreatedNotification($vendor));
        }
    }

    /**
     * Handle the Vendor "updated" event.
     *
     * @param  \App\Vendor  $vendor
     * @return void
     */
    public function updated(Vendor $vendor)
    {
        //
    }

    /**
     * Handle the Vendor "deleted" event.
     *
     * @param  \App\Vendor  $vendor
     * @return void
     */
    public function deleted(Vendor $vendor)
    {
        //
    }
}
