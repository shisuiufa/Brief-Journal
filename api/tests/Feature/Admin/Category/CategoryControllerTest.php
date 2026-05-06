<?php

use App\Enums\Access\PermissionEnum;
use App\Models\Category;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
    $this->seed(RolesSeeder::class);
});

it('returns paginated categories list', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewCategories);

    Category::factory()->count(2)->create();

    $this->actingAs($user, 'api')
        ->getJson('/api/admin/categories')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta',
            'links',
        ])
        ->assertJsonCount(2, 'data');
});

it('creates a category', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::CreateCategories);

    $payload = [
        'name' => 'Laravel',
        'slug' => 'laravel',
    ];

    $this->actingAs($user, 'api')
        ->postJson('/api/admin/categories', $payload)
        ->assertCreated()
        ->assertJsonPath('message', 'Category created successfully.')
        ->assertJsonPath('data.name', 'Laravel')
        ->assertJsonPath('data.slug', 'laravel');

    $this->assertDatabaseHas('categories', $payload);
});

it('shows a category', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewCategories);

    $category = Category::factory()->create();

    $this->actingAs($user, 'api')
        ->getJson("/api/admin/categories/{$category->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $category->id)
        ->assertJsonPath('data.name', $category->name)
        ->assertJsonPath('data.slug', $category->slug);
});

it('updates a category', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::EditCategories);

    $category = Category::factory()->create([
        'name' => 'Old',
        'slug' => 'old',
    ]);

    $payload = [
        'name' => 'Backend',
        'slug' => 'backend',
    ];

    $this->actingAs($user, 'api')
        ->putJson("/api/admin/categories/{$category->id}", $payload)
        ->assertOk()
        ->assertJsonPath('message', 'Category updated successfully.')
        ->assertJsonPath('data.name', 'Backend')
        ->assertJsonPath('data.slug', 'backend');

    $this->assertDatabaseHas('categories', [
        'id' => $category->id,
        ...$payload,
    ]);
});

it('soft deletes a category', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::DeleteCategories);

    $category = Category::factory()->create();

    $this->actingAs($user, 'api')
        ->deleteJson("/api/admin/categories/{$category->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Category deleted successfully.');

    $this->assertSoftDeleted($category);
});

it('forbids editor without manage permissions from creating category', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewCategories);

    $this->actingAs($user, 'api')
        ->postJson('/api/admin/categories', [
            'name' => 'Laravel',
            'slug' => 'laravel',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('categories', [
        'slug' => 'laravel',
    ]);
});

it('requires authentication to access admin categories', function () {
    $this->getJson('/api/admin/categories')
        ->assertUnauthorized();
});
