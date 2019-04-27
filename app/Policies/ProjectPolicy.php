<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $resource
     * @return mixed
     */
    public function view(User $user, Project $resource)
    {
        return $user->can('read-projects');
    }

    /**
     * Determine whether the user can view any projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('read-projects');
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-projects');
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $resource
     * @return mixed
     */
    public function update(User $user, Project $resource)
    {
        return $user->can('update-projects');
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $resource
     * @return mixed
     */
    public function delete(User $user, Project $resource)
    {
        return $user->can('delete-projects');
    }

    /**
     * Determine whether the user can restore the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $resource
     * @return mixed
     */
    public function restore(User $user, Project $resource)
    {
        return $user->can('delete-projects');
    }

    /**
     * Determine whether the user can permanently delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $resource
     * @return mixed
     */
    public function forceDelete(User $user, Project $resource)
    {
        return false;
    }
}
