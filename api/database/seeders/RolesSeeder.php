<?php

namespace Database\Seeders;

use App\Enums\Access\PermissionEnum;
use App\Enums\Access\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::findOrCreate(RoleEnum::SuperAdmin->value);
        $admin = Role::findOrCreate(RoleEnum::Admin->value);
        $editor = Role::findOrCreate(RoleEnum::Editor->value);
        $user = Role::findOrCreate(RoleEnum::User->value);

        $superAdmin->syncPermissions(array_column(PermissionEnum::cases(), 'value'));

        $admin->syncPermissions([
            PermissionEnum::ViewPosts->value,
            PermissionEnum::CreatePosts->value,
            PermissionEnum::EditPosts->value,
            PermissionEnum::DeletePosts->value,
            PermissionEnum::PublishPosts->value,
            PermissionEnum::ViewUsers->value,
            PermissionEnum::CreateEditors->value,
            PermissionEnum::EditEditors->value,
            PermissionEnum::DeleteEditors->value,
        ]);

        $editor->givePermissionTo([
            PermissionEnum::CreatePosts->value,
            PermissionEnum::EditPosts->value,
            PermissionEnum::DeletePosts->value,
            PermissionEnum::PublishPosts->value,
        ]);

        $user->syncPermissions([
            PermissionEnum::ViewPosts->value,
        ]);
    }
}
