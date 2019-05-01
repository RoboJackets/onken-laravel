<?php

namespace App\Policies;

use App\User;
use App\VendorOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vendor order.
     *
     * @param  \App\User  $user
     * @param  \App\VendorOrder  $resource
     * @return mixed
     */
    public function view(User $user, VendorOrder $resource)
    {
        return $user->can('read-vendor-orders');
    }

    /**
     * Determine whether the user can view any vendor orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-vendor-orders');
    }

    /**
     * Determine whether the user can create vendor orders.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-vendor-orders');
    }

    /**
     * Determine whether the user can update the vendor order.
     *
     * @param  \App\User  $user
     * @param  \App\VendorOrder  $resource
     * @return mixed
     */
    public function update(User $user, VendorOrder $resource)
    {
        return $user->can('update-vendor-orders');
    }

    /**
     * Determine whether the user can delete the vendor order.
     *
     * @param  \App\User  $user
     * @param  \App\VendorOrder  $resource
     * @return mixed
     */
    public function delete(User $user, VendorOrder $resource)
    {
        return $user->can('delete-vendor-orders');
    }

    /**
     * Determine whether the user can restore the vendor order.
     *
     * @param  \App\User  $user
     * @param  \App\VendorOrder  $resource
     * @return mixed
     */
    public function restore(User $user, VendorOrder $resource)
    {
        return $user->can('delete-vendor-orders');
    }

    /**
     * Determine whether the user can permanently delete the vendor order.
     *
     * @param  \App\User  $user
     * @param  \App\VendorOrder  $resource
     * @return mixed
     */
    public function forceDelete(User $user, VendorOrder $resource)
    {
        return false;
    }
}
