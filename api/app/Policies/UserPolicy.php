<?php

namespace App\Policies;

use App\Enums\Access\PermissionEnum;
use App\Enums\Access\RoleEnum;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewUsers->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->can(PermissionEnum::ViewUsers->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateEditors->value)
            || $user->can(PermissionEnum::CreateAdmins->value);
    }

    /**
     * Determine whether the user can create model with specific role.
     */
    public function createWithRole(User $user, RoleEnum $role): bool
    {
        return match ($role) {
            RoleEnum::Admin => $user->can(PermissionEnum::CreateAdmins->value),

            RoleEnum::Editor => $user->can(PermissionEnum::CreateEditors->value),

            RoleEnum::User, RoleEnum::SuperAdmin => false,
        };
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::EditAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::EditEditors->value);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::DeleteAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::DeleteEditors->value);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::EditAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::EditEditors->value);
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::DeleteAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::DeleteEditors->value);
        }

        return false;
    }
}
