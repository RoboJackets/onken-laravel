<?php

namespace App\Policies;

use App\User;
use App\Requisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequisitionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the requisition.
     *
     * @param  \App\User  $user
     * @param  \App\Requisition  $resource
     * @return mixed
     */
    public function view(User $user, Requisition $resource)
    {
        return $user->can('read-requisitions');
    }

    /**
     * Determine whether the user can view any requisitions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-requisitions');
    }

    /**
     * Determine whether the user can create requisitions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-requisitions');
    }

    /**
     * Determine whether the user can update the requisition.
     *
     * @param  \App\User  $user
     * @param  \App\Requisition  $resource
     * @return mixed
     */
    public function update(User $user, Requisition $resource)
    {
        $req_state = $resource->state;
        return $user->can('update-requisitions-locked') || (($req_state == 'draft' || $req_state == 'pending_approval') && $user->can('update-requisitions'));
    }

    /**
     * Determine whether the user can delete the requisition.
     *
     * @param  \App\User  $user
     * @param  \App\Requisition  $resource
     * @return mixed
     */
    public function delete(User $user, Requisition $resource)
    {
        $req_state = $resource->state;
        return $user->can('delete-requisitions-locked') || (($req_state == 'draft' || $req_state == 'pending_approval') && $user->can('delete-requisitions'));
    }

    /**
     * Determine whether the user can restore the requisition.
     *
     * @param  \App\User  $user
     * @param  \App\Requisition  $resource
     * @return mixed
     */
    public function restore(User $user, Requisition $resource)
    {
        return $user->can('delete-requisitions-locked');
    }

    /**
     * Determine whether the user can permanently delete the requisition.
     *
     * @param  \App\User  $user
     * @param  \App\Requisition  $resource
     * @return mixed
     */
    public function forceDelete(User $user, Requisition $resource)
    {
        return false;
    }
}
