<?php

namespace App\Policies;

use App\User;
use App\Tale;
use Illuminate\Auth\Access\HandlesAuthorization;

class TalePolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any tales.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Tale  $tale
     * @return mixed
     */
    public function view(User $user, Tale $tale)
    {
        return true;
    }

    /**
     * Determine whether the user can create tales.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return Auth::check() ? true : false;
    }

    /**
     * Determine whether the user can update the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Tale  $tale
     * @return mixed
     */
    public function update(User $user, Tale $tale)
    {
        return Auth::check() ? true : false;
    }

    /**
     * Determine whether the user can delete the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Tale  $tale
     * @return mixed
     */
    public function delete(User $user, Tale $tale)
    {
        return Auth::check() ? true : false;
    }

    /**
     * Determine whether the user can restore the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Tale  $tale
     * @return mixed
     */
    public function restore(User $user, Tale $tale)
    {
        return Auth::check() ? true : false;
    }

    /**
     * Determine whether the user can permanently delete the tale.
     *
     * @param  \App\User  $user
     * @param  \App\Tale  $tale
     * @return mixed
     */
    public function forceDelete(User $user, Tale $tale)
    {
        return Auth::check() ? true : false;
    }
}
