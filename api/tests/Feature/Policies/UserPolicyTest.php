<?php

use App\Enums\Access\RoleEnum;
use App\Models\User;
use App\Policies\UserPolicy;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
    $this->seed(RolesSeeder::class);

    $this->policy = new UserPolicy();
});

function policyUser(RoleEnum $role): User
{
    $user = User::factory()->create();

    $user->assignRole($role->value);

    return $user;
}

describe('createWithRole', function () {
    it('allows super admin to create any allowed role', function (RoleEnum $role) {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->createWithRole($superAdmin, $role))->toBeTrue();
    })->with([
        'admin' => [RoleEnum::Admin],
        'editor' => [RoleEnum::Editor],
        'user' => [RoleEnum::User],
        'super admin' => [RoleEnum::SuperAdmin],
    ]);

    it('allows admin to create only editor', function () {
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->createWithRole($admin, RoleEnum::Editor))->toBeTrue()
            ->and($this->policy->createWithRole($admin, RoleEnum::Admin))->toBeFalse()
            ->and($this->policy->createWithRole($admin, RoleEnum::SuperAdmin))->toBeFalse()
            ->and($this->policy->createWithRole($admin, RoleEnum::User))->toBeFalse();
    });

    it('forbids editor to create users', function () {
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->createWithRole($editor, RoleEnum::Editor))->toBeFalse();
    });
});

describe('update', function () {
    it('allows super admin to update another user and change role', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($superAdmin, $editor, RoleEnum::Admin))->toBeTrue();
    });

    it('forbids super admin to change his own role', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->update($superAdmin, $superAdmin, RoleEnum::Admin))->toBeFalse();
    });

    it('allows super admin to update himself without changing role', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->update($superAdmin, $superAdmin, RoleEnum::SuperAdmin))->toBeTrue();
    });

    it('allows admin to update editor without changing role', function () {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($admin, $editor, RoleEnum::Editor))->toBeTrue();
    });

    it('forbids admin to change editor role', function (RoleEnum $newRole) {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($admin, $editor, $newRole))->toBeFalse();
    })->with([
        'admin' => [RoleEnum::Admin],
        'super admin' => [RoleEnum::SuperAdmin],
        'user' => [RoleEnum::User],
    ]);

    it('forbids admin to update another admin', function () {
        $admin = policyUser(RoleEnum::Admin);
        $anotherAdmin = policyUser(RoleEnum::Admin);

        expect($this->policy->update($admin, $anotherAdmin, RoleEnum::Admin))->toBeFalse();
    });

    it('allows admin to update himself without changing role', function () {
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->update($admin, $admin, RoleEnum::Admin))->toBeTrue();
    });

    it('forbids admin to change his own role', function () {
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->update($admin, $admin, RoleEnum::Editor))->toBeFalse();
    });

    it('allows editor to update himself without changing role', function () {
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($editor, $editor, RoleEnum::Editor))->toBeTrue();
    });

    it('forbids editor to change his own role', function () {
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($editor, $editor, RoleEnum::Admin))->toBeFalse();
    });

    it('forbids editor to update another editor', function () {
        $editor = policyUser(RoleEnum::Editor);
        $anotherEditor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($editor, $anotherEditor, RoleEnum::Editor))->toBeFalse();
    });
});

describe('delete', function () {
    it('allows super admin to delete admin', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->delete($superAdmin, $admin))->toBeTrue();
    });

    it('allows super admin to delete editor', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->delete($superAdmin, $editor))->toBeTrue();
    });

    it('forbids user to delete himself', function () {
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->delete($admin, $admin))->toBeFalse();
    });

    it('allows admin to delete editor', function () {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->delete($admin, $editor))->toBeTrue();
    });

    it('forbids admin to delete admin', function () {
        $admin = policyUser(RoleEnum::Admin);
        $anotherAdmin = policyUser(RoleEnum::Admin);

        expect($this->policy->delete($admin, $anotherAdmin))->toBeFalse();
    });

    it('forbids editor to delete editor', function () {
        $editor = policyUser(RoleEnum::Editor);
        $anotherEditor = policyUser(RoleEnum::Editor);

        expect($this->policy->delete($editor, $anotherEditor))->toBeFalse();
    });
});
