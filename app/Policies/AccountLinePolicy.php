<?php

namespace App\Policies;

use App\User;
use App\AccountLine;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountLinePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the account line.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLine  $resource
     * @return mixed
     */
    public function view(User $user, AccountLine $resource)
    {
        return $user->can('read-account-lines');
    }

    /**
     * Determine whether the user can view any account lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-account-lines');
    }

    /**
     * Determine whether the user can create account lines.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('read-account-lines');
    }

    /**
     * Determine whether the user can update the account line.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLine  $resource
     * @return mixed
     */
    public function update(User $user, AccountLine $resource)
    {
        return $user->can('update-account-lines');
    }

    /**
     * Determine whether the user can delete the account line.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLine  $resource
     * @return mixed
     */
    public function delete(User $user, AccountLine $resource)
    {
        return $user->can('delete-account-lines');
    }

    /**
     * Determine whether the user can restore the account line.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLine  $resource
     * @return mixed
     */
    public function restore(User $user, AccountLine $resource)
    {
        return $user->can('delete-account-lines');
    }

    /**
     * Determine whether the user can permanently delete the account line.
     *
     * @param  \App\User  $user
     * @param  \App\AccountLine  $resource
     * @return mixed
     */
    public function forceDelete(User $user, AccountLine $resource)
    {
        return false;
    }
}
