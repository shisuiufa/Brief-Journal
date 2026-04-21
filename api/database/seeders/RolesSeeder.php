<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::findOrCreate(RoleEnum::ADMIN->value);
        $editor = Role::findOrCreate(RoleEnum::EDITOR->value);

        $admin->givePermissionTo(array_column(PermissionEnum::cases(), 'value'));

        $editor->givePermissionTo([
            PermissionEnum::CREATE_POSTS->value,
            PermissionEnum::EDIT_POSTS->value,
            PermissionEnum::DELETE_POSTS->value,
            PermissionEnum::PUBLISH_POSTS->value,
        ]);
    }
}
