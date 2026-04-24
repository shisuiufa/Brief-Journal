<?php

namespace App\Policies;

use App\Enums\Access\PermissionEnum;
use App\Enums\Access\RoleEnum;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can(PermissionEnum::ViewUsers->value);
    }

    public function view(User $user, User $model): bool
    {
        return $user->can(PermissionEnum::ViewUsers->value);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionEnum::CreateEditors->value)
            || $user->can(PermissionEnum::CreateAdmins->value);
    }

    public function createWithRole(User $user, RoleEnum $role): bool
    {
        if ($this->isSuperAdmin($user)) {
            return in_array($role, [
                RoleEnum::Admin,
                RoleEnum::Editor,
            ], true);
        }

        return match ($role) {
            RoleEnum::Editor => $user->can(PermissionEnum::CreateEditors->value),
            RoleEnum::Admin,
            RoleEnum::SuperAdmin,
            RoleEnum::User => false,
        };
    }

    public function update(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return true;
        }

        if ($this->isSuperAdmin($model)) {
            return $this->isSuperAdmin($user);
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->canEditTarget($user, $model);
    }

    public function changeRole(User $user, User $model, RoleEnum $newRole): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($this->isSuperAdmin($model)) {
            return false;
        }

        if ($newRole === RoleEnum::SuperAdmin) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $user->hasRole(RoleEnum::Admin->value)
            && $model->hasRole(RoleEnum::Editor->value)
            && $newRole === RoleEnum::Editor;
    }

    public function delete(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($this->isSuperAdmin($model)) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->canDeleteTarget($user, $model);
    }

    public function restore(User $user, User $model): bool
    {
        if ($this->isSuperAdmin($model)) {
            return $this->isSuperAdmin($user);
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->canEditTarget($user, $model);
    }

    public function forceDelete(User $user, User $model): bool
    {
        if ($model->is($user)) {
            return false;
        }

        if ($this->isSuperAdmin($model)) {
            return false;
        }

        if ($this->isSuperAdmin($user)) {
            return true;
        }

        return $this->canDeleteTarget($user, $model);
    }

    private function canEditTarget(User $user, User $model): bool
    {
        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::EditAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::EditEditors->value);
        }

        return false;
    }

    private function canDeleteTarget(User $user, User $model): bool
    {
        if ($model->hasRole(RoleEnum::Admin->value)) {
            return $user->can(PermissionEnum::DeleteAdmins->value);
        }

        if ($model->hasRole(RoleEnum::Editor->value)) {
            return $user->can(PermissionEnum::DeleteEditors->value);
        }

        return false;
    }

    private function isSuperAdmin(User $user): bool
    {
        return $user->hasRole(RoleEnum::SuperAdmin->value);
    }
}
