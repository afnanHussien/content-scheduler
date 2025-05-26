<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    { 
    }

    private function isOwner(User $user, Post $post): bool
    {
        return $user->id == $post->user_id;
    }

    public function view(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }

    public function update(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }

    public function delete(User $user, Post $post): bool
    {
        return $this->isOwner($user, $post);
    }
}
