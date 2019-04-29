<?php

namespace App\Policies;

use App\User;
use App\Approval;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApprovalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the approval.
     *
     * @param  \App\User  $user
     * @param  \App\Approval  $resource
     * @return mixed
     */
    public function view(User $user, Approval $resource)
    {
        return $user->can('read-approvals');
    }

    /**
     * Determine whether the user can view any approvals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-approvals');
    }

    /**
     * Determine whether the user can create approvals.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // Approvals can't be created like this, they're created by actions
        return false;
    }

    /**
     * Determine whether the user can update the approval.
     *
     * @param  \App\User  $user
     * @param  \App\Approval  $resource
     * @return mixed
     */
    public function update(User $user, Approval $resource)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the approval.
     *
     * @param  \App\User  $user
     * @param  \App\Approval  $resource
     * @return mixed
     */
    public function delete(User $user, Approval $resource)
    {
        // This only happens with actions
        return false;
    }

    /**
     * Determine whether the user can restore the approval.
     *
     * @param  \App\User  $user
     * @param  \App\Approval  $resource
     * @return mixed
     */
    public function restore(User $user, Approval $resource)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the approval.
     *
     * @param  \App\User  $user
     * @param  \App\Approval  $resource
     * @return mixed
     */
    public function forceDelete(User $user, Approval $resource)
    {
        return false;
    }
}
