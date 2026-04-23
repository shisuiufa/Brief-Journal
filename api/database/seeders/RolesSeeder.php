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
        $admin = Role::findOrCreate(RoleEnum::Admin->value);
        $editor = Role::findOrCreate(RoleEnum::Editor->value);

        $admin->givePermissionTo(array_column(PermissionEnum::cases(), 'value'));

        $editor->givePermissionTo([
            PermissionEnum::CreatePosts->value,
            PermissionEnum::EditPosts->value,
            PermissionEnum::DeletePosts->value,
            PermissionEnum::PublishPosts->value,
        ]);
    }
}
