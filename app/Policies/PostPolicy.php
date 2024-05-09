<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /*
     * Determine whether the user can view any models.
     *
    public function viewAny(User $user): bool
    {
        //
    }*/

    /*
     * Determine whether the user can view the model.
     *
    public function view(User $user, Post $post): bool
    {
        //
    }*/

    /*
     * Determine whether the user can create models.
     *
    public function create(User $user): bool
    {
        //
    }*/

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if(($post->user()->is($user)) || ($user->is_admin)) {
            $response = true;
        }
        else {
            $response = false;
        }
        return $response;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $this->update($user, $post);  /**Instead of repeating the logic from the "update" method, we call the update method. 
                                               *A user who is authorized to update a post, is authorized to delete it as well.*/
    }

    /*
     * Determine whether the user can restore the model.
     *
    public function restore(User $user, Post $post): bool
    {
        //
    }*/

    /*
     * Determine whether the user can permanently delete the model.
     *
    public function forceDelete(User $user, Post $post): bool
    {
        //
    }*/
}
