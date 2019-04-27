<?php

namespace App\Policies;

use App\User;
use App\RequisitionLine;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequisitionLinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the requisition line.
     *
     * @param  \App\User  $user
     * @param  \App\RequisitionLine  $resource
     * @return mixed
     */
    public function view(User $user, RequisitionLine $resource)
    {
        return $user->can('read-requisition-lines');
    }

    /**
     * Determine whether the user can view any requisition lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-requisition-lines');
    }

    /**
     * Determine whether the user can create requisition lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-requisition-lines');
    }

    /**
     * Determine whether the user can update the requisition line.
     *
     * @param  \App\User  $user
     * @param  \App\RequisitionLine  $resource
     * @return mixed
     */
    public function update(User $user, RequisitionLine $resource)
    {
        $req_state = $resource->requisition->state;
        return $user->can('update-requisition-lines-locked') || (($req_state == 'draft' || $req_state == 'pending_approval') && $user->can('update-requisition-lines'));
    }

    /**
     * Determine whether the user can delete the requisition line.
     *
     * @param  \App\User  $user
     * @param  \App\RequisitionLine  $resource
     * @return mixed
     */
    public function delete(User $user, RequisitionLine $resource)
    {
        $req_state = $resource->requisition->state;
        return $user->can('delete-requisition-lines-locked') || (($req_state == 'draft' || $req_state == 'pending_approval') && $user->can('delete-requisition-lines'));
    }

    /**
     * Determine whether the user can restore the requisition line.
     *
     * @param  \App\User  $user
     * @param  \App\RequisitionLine  $resource
     * @return mixed
     */
    public function restore(User $user, RequisitionLine $resource)
    {
        return $user->can('delete-requisition-lines-locked');
    }

    /**
     * Determine whether the user can permanently delete the requisition line.
     *
     * @param  \App\User  $user
     * @param  \App\RequisitionLine  $resource
     * @return mixed
     */
    public function forceDelete(User $user, RequisitionLine $resource)
    {
        return false;
    }
}
