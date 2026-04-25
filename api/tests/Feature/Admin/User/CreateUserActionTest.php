<?php

use App\Contracts\Admin\User\CreateUserActionInterface;
use App\Data\Admin\User\CreateUserData;
use App\Enums\Access\RoleEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate(RoleEnum::Admin->value);
});

function createUser(CreateUserData $data): User
{
    return app(CreateUserActionInterface::class)->execute($data);
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

    expect($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('test@example.com');

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'John Doe',
        'email' => 'test@example.com',
    ]);
});

it('assigns user role', function () {
    $user = createUser(createUserData());

    expect($user->hasRole(RoleEnum::Admin->value))->toBeTrue();
});

it('stores user password hashed', function () {
    $user = createUser(createUserData());

    expect($user->password)->not->toBe('password')
        ->and(Hash::check('password', $user->password))->toBeTrue();
});
