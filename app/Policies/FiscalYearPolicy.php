<?php

namespace App\Policies;

use App\User;
use App\FiscalYear;
use Illuminate\Auth\Access\HandlesAuthorization;

class FiscalYearPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the fiscal year.
     *
     * @param  \App\User  $user
     * @param  \App\FiscalYear  $resource
     * @return mixed
     */
    public function view(User $user, FiscalYear $resource)
    {
        return $user->can('read-fiscal-years');
    }

    /**
     * Determine whether the user can view any fiscal years.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-fiscal-years');
    }

    /**
     * Determine whether the user can create fiscal years.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-fiscal-years');
    }

    /**
     * Determine whether the user can update the fiscal year.
     *
     * @param  \App\User  $user
     * @param  \App\FiscalYear  $resource
     * @return mixed
     */
    public function update(User $user, FiscalYear $resource)
    {
        return $user->can('update-fiscal-years');
    }

    /**
     * Determine whether the user can delete the fiscal year.
     *
     * @param  \App\User  $user
     * @param  \App\FiscalYear  $resource
     * @return mixed
     */
    public function delete(User $user, FiscalYear $resource)
    {
        return $user->can('delete-fiscal-years');
    }

    /**
     * Determine whether the user can restore the fiscal year.
     *
     * @param  \App\User  $user
     * @param  \App\FiscalYear  $resource
     * @return mixed
     */
    public function restore(User $user, FiscalYear $resource)
    {
        return $user->can('delete-fiscal-years');
    }

    /**
     * Determine whether the user can permanently delete the fiscal year.
     *
     * @param  \App\User  $user
     * @param  \App\FiscalYear  $resource
     * @return mixed
     */
    public function forceDelete(User $user, FiscalYear $resource)
    {
        return false;
    }
}
