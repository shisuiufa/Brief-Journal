<?php

namespace App\Policies;

use App\Enums\Access\PermissionEnum;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewPosts->value);
    }

    public function view(User $user, Post $post): bool
    {
        return $user->can(PermissionEnum::ViewPosts->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreatePosts->value);
    }

    public function update(User $user, Post $post): bool
    {
        return $user->can(PermissionEnum::EditPosts->value);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->can(PermissionEnum::DeletePosts->value);
    }

    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
