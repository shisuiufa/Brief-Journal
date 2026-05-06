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
        $superAdmin = Role::findOrCreate(RoleEnum::SuperAdmin->value, 'api');
        $admin = Role::findOrCreate(RoleEnum::Admin->value, 'api');
        $editor = Role::findOrCreate(RoleEnum::Editor->value, 'api');
        $user = Role::findOrCreate(RoleEnum::User->value, 'api');

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
            PermissionEnum::ViewCategories->value,
            PermissionEnum::CreateCategories->value,
            PermissionEnum::EditCategories->value,
            PermissionEnum::DeleteCategories->value,
            PermissionEnum::ViewTags->value,
            PermissionEnum::CreateTags->value,
            PermissionEnum::EditTags->value,
            PermissionEnum::DeleteTags->value,
        ]);

        $editor->givePermissionTo([
            PermissionEnum::ViewPosts->value,
            PermissionEnum::CreatePosts->value,
            PermissionEnum::EditPosts->value,
            PermissionEnum::DeletePosts->value,
            PermissionEnum::PublishPosts->value,
            PermissionEnum::ViewCategories->value,
            PermissionEnum::ViewTags->value,
        ]);

        $user->syncPermissions([
            PermissionEnum::ViewPosts->value,
        ]);
    }
}
