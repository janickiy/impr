<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use App\Models\Permission\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param User|null $user
     * @param Post      $post
     *
     * @return bool
     */
    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::PERMISSION_CREATE_POST);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Post $post
     *
     * @return bool
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id
            && $user->can(Permission::PERMISSION_UPDATE_POST);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Post $post
     *
     * @return bool
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id
            && $user->can(Permission::PERMISSION_DELETE_POST);
    }

    /**
     * Determine whether the user can edit the model.
     *
     * @param User $user
     * @param Post $post
     *
     * @return bool
     */
    public function edit(User $user, Post $post): bool
    {
        return $user->id === $post->user_id
            && $user->can(Permission::PERMISSION_UPDATE_POST);
    }
}
