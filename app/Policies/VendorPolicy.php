<?php

namespace App\Policies;

use App\User;
use App\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vendor.
     *
     * @param  \App\User  $user
     * @param  \App\Vendor  $resource
     * @return mixed
     */
    public function view(User $user, Vendor $resource)
    {
        return $user->can('read-vendors');
    }

    /**
     * Determine whether the user can view any vendors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-vendors');
    }

    /**
     * Determine whether the user can create vendors.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-vendors');
    }

    /**
     * Determine whether the user can update the vendor.
     *
     * @param  \App\User  $user
     * @param  \App\Vendor  $resource
     * @return mixed
     */
    public function update(User $user, Vendor $resource)
    {
        return $user->can('update-vendors');
    }

    /**
     * Determine whether the user can delete the vendor.
     *
     * @param  \App\User  $user
     * @param  \App\Vendor  $resource
     * @return mixed
     */
    public function delete(User $user, Vendor $resource)
    {
        return $user->can('delete-vendors');
    }

    /**
     * Determine whether the user can restore the vendor.
     *
     * @param  \App\User  $user
     * @param  \App\Vendor  $resource
     * @return mixed
     */
    public function restore(User $user, Vendor $resource)
    {
        return $user->can('delete-vendors');
    }

    /**
     * Determine whether the user can permanently delete the vendor.
     *
     * @param  \App\User  $user
     * @param  \App\Vendor  $resource
     * @return mixed
     */
    public function forceDelete(User $user, Vendor $resource)
    {
        return false;
    }
}
