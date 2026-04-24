<?php

use App\Contracts\Admin\User\UpdateUserActionInterface;
use App\Data\Admin\UpdateUserData;
use App\Enums\Access\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::Admin->value);
    Role::findOrCreate(RoleEnum::Editor->value);
});

function updateUser(User $user, UpdateUserData $updateUserData): User
{
    return app(UpdateUserActionInterface::class)->execute($user, $updateUserData);
}

function createUpdateUserData(?RoleEnum $role = RoleEnum::Admin): UpdateUserData
{
    return new UpdateUserData(
        name: 'New Name',
        email: 'new@example.com',
        role: $role,
    );
}

it('updates user name and email', function () {
    $user = User::factory()->create([
        'name' => 'Old Name',
        'email' => 'old@example.com',
    ]);

    $updatedUser = updateUser(
        $user,
        createUpdateUserData(role: null),
    );

    expect($updatedUser->name)->toBe('New Name')
        ->and($updatedUser->email)->toBe('new@example.com');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'New Name',
        'email' => 'new@example.com',
    ]);
});

it('syncs user role', function () {
    $user = User::factory()->create();
    $user->assignRole(RoleEnum::Editor->value);

    $updatedUser = updateUser(
        $user,
        createUpdateUserData(),
    );

    expect($updatedUser->hasRole(RoleEnum::Admin->value))->toBeTrue()
        ->and($updatedUser->hasRole(RoleEnum::Editor->value))->toBeFalse();
});
