<?php

namespace App\Policies;

use App\User;
use App\VendorTag;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorTagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vendor tag.
     *
     * @param  \App\User  $user
     * @param  \App\VendorTag  $resource
     * @return mixed
     */
    public function view(User $user, VendorTag $resource)
    {
        return $user->can('read-vendor-tags');
    }

    /**
     * Determine whether the user can view any vendor tags.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
        // return $user->can('read-vendor-tags');
    }

    /**
     * Determine whether the user can create vendor tags.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-vendor-tags');
    }

    /**
     * Determine whether the user can update the vendor tag.
     *
     * @param  \App\User  $user
     * @param  \App\VendorTag  $resource
     * @return mixed
     */
    public function update(User $user, VendorTag $resource)
    {
        return $user->can('update-vendor-tags');
    }

    /**
     * Determine whether the user can delete the vendor tag.
     *
     * @param  \App\User  $user
     * @param  \App\VendorTag  $resource
     * @return mixed
     */
    public function delete(User $user, VendorTag $resource)
    {
        return $user->can('delete-vendor-tags');
    }

    /**
     * Determine whether the user can restore the vendor tag.
     *
     * @param  \App\User  $user
     * @param  \App\VendorTag  $resource
     * @return mixed
     */
    public function restore(User $user, VendorTag $resource)
    {
        return $user->can('delete-vendor-tags');
    }

    /**
     * Determine whether the user can permanently delete the vendor tag.
     *
     * @param  \App\User  $user
     * @param  \App\VendorTag  $resource
     * @return mixed
     */
    public function forceDelete(User $user, VendorTag $resource)
    {
        return false;
    }
}
