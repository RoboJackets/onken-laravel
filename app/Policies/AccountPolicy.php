<?php

namespace App\Policies;

use App\User;
use App\Account;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $resource
     * @return mixed
     */
    public function view(User $user, Account $resource)
    {
        return $user->can('read-accounts');
    }

    /**
     * Determine whether the user can view any accounts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-accounts');
    }

    /**
     * Determine whether the user can create accounts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-accounts');
    }

    /**
     * Determine whether the user can update the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $resource
     * @return mixed
     */
    public function update(User $user, Account $resource)
    {
        return $user->can('update-accounts');
    }

    /**
     * Determine whether the user can delete the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $resource
     * @return mixed
     */
    public function delete(User $user, Account $resource)
    {
        return $user->can('delete-accounts');
    }

    /**
     * Determine whether the user can restore the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $resource
     * @return mixed
     */
    public function restore(User $user, Account $resource)
    {
        return $user->can('delete-accounts');
    }

    /**
     * Determine whether the user can permanently delete the account.
     *
     * @param  \App\User  $user
     * @param  \App\Account  $resource
     * @return mixed
     */
    public function forceDelete(User $user, Account $resource)
    {
        return false;
    }
}
