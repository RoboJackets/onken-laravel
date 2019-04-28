<?php

namespace App\Policies;

use App\User;
use App\VendorNote;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorNotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vendor note.
     *
     * @param  \App\User  $user
     * @param  \App\VendorNote  $resource
     * @return mixed
     */
    public function view(User $user, VendorNote $resource)
    {
        return $user->can('read-vendor-notes');
    }

    /**
     * Determine whether the user can view any vendor notes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-vendor-notes');
    }

    /**
     * Determine whether the user can create vendor notes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-vendor-notes');
    }

    /**
     * Determine whether the user can update the vendor note.
     *
     * @param  \App\User  $user
     * @param  \App\VendorNote  $resource
     * @return mixed
     */
    public function update(User $user, VendorNote $resource)
    {
        return $user->can('update-vendor-notes') || ($user->can('update-vendor-notes-own') && $user->is($resource->user));
    }

    /**
     * Determine whether the user can delete the vendor note.
     *
     * @param  \App\User  $user
     * @param  \App\VendorNote  $resource
     * @return mixed
     */
    public function delete(User $user, VendorNote $resource)
    {
        return $user->can('delete-vendor-notes') || ($user->can('delete-vendor-notes-own') && $user->is($resource->user));
    }

    /**
     * Determine whether the user can restore the vendor note.
     *
     * @param  \App\User  $user
     * @param  \App\VendorNote  $resource
     * @return mixed
     */
    public function restore(User $user, VendorNote $resource)
    {
        return $user->can('delete-vendor-notes');
    }

    /**
     * Determine whether the user can permanently delete the vendor note.
     *
     * @param  \App\User  $user
     * @param  \App\VendorNote  $resource
     * @return mixed
     */
    public function forceDelete(User $user, VendorNote $resource)
    {
        return false;
    }
}
