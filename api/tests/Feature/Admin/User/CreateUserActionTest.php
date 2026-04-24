<?php

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Data\Admin\CreateUserData;
use App\Enums\Access\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::Admin->value);
});

function createUser(CreateUserData $createUserData): User
{
    return app(CreateUserActionInterface::class)->execute($createUserData);
}

function createUserData(): CreateUserData
{
    return new CreateUserData(
        name: 'John Doe',
        email: 'test@example.com',
        password: 'password',
        role: RoleEnum::Admin,
    );
}

it('creates a user', function () {
    $user = createUser(createUserData());

    expect($user->email)->toBe('test@example.com');

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('assigns the admin role', function () {
    $user = createUser(createUserData());

    expect($user->hasRole(RoleEnum::Admin->value))->toBeTrue();
});

it('hashes the user password', function () {
    $user = createUser(createUserData());

    expect(Hash::check('password', $user->password))->toBeTrue();
});
