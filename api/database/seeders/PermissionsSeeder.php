<?php

namespace Database\Seeders;

use App\Enums\Access\PermissionEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentPermissions = collect(PermissionEnum::cases())
            ->map(fn (PermissionEnum $permission) => $permission->value)
            ->values()
            ->all();

        foreach ($currentPermissions as $permission) {
            Permission::findOrCreate($permission);
        }

        Permission::whereNotIn('name', $currentPermissions)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
