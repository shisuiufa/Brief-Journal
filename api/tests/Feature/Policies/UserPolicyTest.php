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

    $this->policy = new UserPolicy;
});

describe('createWithRole', function () {
    it('allows super admin to create admin and editor', function (RoleEnum $role) {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->createWithRole($superAdmin, $role))->toBeTrue();
    })->with([
        'admin' => [RoleEnum::Admin],
        'editor' => [RoleEnum::Editor],
    ]);

    it('forbids super admin to create user or another super admin', function (RoleEnum $role) {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->createWithRole($superAdmin, $role))->toBeFalse();
    })->with([
        'user' => [RoleEnum::User],
        'super admin' => [RoleEnum::SuperAdmin],
    ]);

    it('forbids super admin to create another super admin', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->createWithRole($superAdmin, RoleEnum::SuperAdmin))->toBeFalse();
    });

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
    it('allows super admin to update another user', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($superAdmin, $editor))->toBeTrue();
    });

    it('allows super admin to update himself', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->update($superAdmin, $superAdmin))->toBeTrue();
    });

    it('allows admin to update himself', function () {
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->update($admin, $admin))->toBeTrue();
    });

    it('allows editor to update himself', function () {
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($editor, $editor))->toBeTrue();
    });

    it('allows admin to update editor', function () {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($admin, $editor))->toBeTrue();
    });

    it('forbids admin to update another admin', function () {
        $admin = policyUser(RoleEnum::Admin);
        $anotherAdmin = policyUser(RoleEnum::Admin);

        expect($this->policy->update($admin, $anotherAdmin))->toBeFalse();
    });

    it('forbids admin to update super admin', function () {
        $admin = policyUser(RoleEnum::Admin);
        $superAdmin = policyUser(RoleEnum::SuperAdmin);

        expect($this->policy->update($admin, $superAdmin))->toBeFalse();
    });

    it('forbids editor to update another editor', function () {
        $editor = policyUser(RoleEnum::Editor);
        $anotherEditor = policyUser(RoleEnum::Editor);

        expect($this->policy->update($editor, $anotherEditor))->toBeFalse();
    });
});

describe('changeRole', function () {
    it('forbids user to change own role', function (RoleEnum $role, RoleEnum $newRole) {
        $user = policyUser($role);

        expect($this->policy->changeRole($user, $user, $newRole))->toBeFalse();
    })->with([
        'super admin to admin' => [RoleEnum::SuperAdmin, RoleEnum::Admin],
        'admin to editor' => [RoleEnum::Admin, RoleEnum::Editor],
        'editor to admin' => [RoleEnum::Editor, RoleEnum::Admin],
    ]);

    it('forbids changing role of super admin', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $admin = policyUser(RoleEnum::Admin);

        expect($this->policy->changeRole($admin, $superAdmin, RoleEnum::Editor))->toBeFalse()
            ->and($this->policy->changeRole($superAdmin, $superAdmin, RoleEnum::Admin))->toBeFalse();
    });

    it('forbids assigning super admin role', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->changeRole($superAdmin, $editor, RoleEnum::SuperAdmin))->toBeFalse();
    });

    it('allows super admin to change admin or editor role to allowed role', function () {
        $superAdmin = policyUser(RoleEnum::SuperAdmin);
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->changeRole($superAdmin, $admin, RoleEnum::Editor))->toBeTrue()
            ->and($this->policy->changeRole($superAdmin, $editor, RoleEnum::Admin))->toBeTrue()
            ->and($this->policy->changeRole($superAdmin, $editor, RoleEnum::User))->toBeTrue();
    });

    it('forbids admin to promote editor to admin', function () {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->changeRole($admin, $editor, RoleEnum::Admin))->toBeFalse();
    });

    it('allows admin to keep editor role only', function () {
        $admin = policyUser(RoleEnum::Admin);
        $editor = policyUser(RoleEnum::Editor);

        expect($this->policy->changeRole($admin, $editor, RoleEnum::Editor))->toBeTrue();
    });

    it('forbids editor to change roles', function () {
        $editor = policyUser(RoleEnum::Editor);
        $anotherEditor = policyUser(RoleEnum::Editor);

        expect($this->policy->changeRole($editor, $anotherEditor, RoleEnum::Admin))->toBeFalse();
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
