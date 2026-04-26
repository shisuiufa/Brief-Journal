<?php

use App\Enums\Access\PermissionEnum;
use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);

    $this->policy = new PostPolicy();
    $this->post = Post::factory()
        ->for(User::factory(), 'author')
        ->create();
});

it('allows post actions with required permission', function (
    string $method,
    PermissionEnum $permission,
) {
    $user = User::factory()->create();
    $user->givePermissionTo($permission->value);

    $result = in_array($method, ['viewAny', 'create'], true)
        ? $this->policy->{$method}($user)
        : $this->policy->{$method}($user, $this->post);

    expect($result)->toBeTrue();
})->with([
    'viewAny' => ['viewAny', PermissionEnum::ViewPosts],
    'view' => ['view', PermissionEnum::ViewPosts],
    'create' => ['create', PermissionEnum::CreatePosts],
    'update' => ['update', PermissionEnum::EditPosts],
    'delete' => ['delete', PermissionEnum::DeletePosts],
]);

it('denies post actions without required permission', function (
    string $method,
) {
    $user = User::factory()->create();

    $result = in_array($method, ['viewAny', 'create'], true)
        ? $this->policy->{$method}($user)
        : $this->policy->{$method}($user, $this->post);

    expect($result)->toBeFalse();
})->with([
    'viewAny' => ['viewAny'],
    'view' => ['view'],
    'create' => ['create'],
    'update' => ['update'],
    'delete' => ['delete'],
]);

it('always denies restore and force delete', function (string $method) {
    $user = User::factory()->create();

    expect($this->policy->{$method}($user, $this->post))->toBeFalse();
})->with([
    'restore' => ['restore'],
    'forceDelete' => ['forceDelete'],
]);
