<?php

use App\Enums\Access\PermissionEnum;
use App\Models\Tag;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
    $this->seed(RolesSeeder::class);
});

it('returns paginated tags list', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewTags);

    Tag::factory()->count(2)->create();

    $this->actingAs($user, 'api')
        ->getJson('/api/admin/tags')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta',
            'links',
        ])
        ->assertJsonCount(2, 'data');
});

it('creates a tag', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::CreateTags);

    $payload = [
        'name' => 'Passport',
        'slug' => 'passport',
    ];

    $this->actingAs($user, 'api')
        ->postJson('/api/admin/tags', $payload)
        ->assertCreated()
        ->assertJsonPath('message', 'Tag created successfully.')
        ->assertJsonPath('data.name', 'Passport')
        ->assertJsonPath('data.slug', 'passport');

    $this->assertDatabaseHas('tags', $payload);
});

it('shows a tag', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewTags);

    $tag = Tag::factory()->create();

    $this->actingAs($user, 'api')
        ->getJson("/api/admin/tags/{$tag->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $tag->id)
        ->assertJsonPath('data.name', $tag->name)
        ->assertJsonPath('data.slug', $tag->slug);
});

it('updates a tag', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::EditTags);

    $tag = Tag::factory()->create([
        'name' => 'Old',
        'slug' => 'old',
    ]);

    $payload = [
        'name' => 'OAuth',
        'slug' => 'oauth',
    ];

    $this->actingAs($user, 'api')
        ->putJson("/api/admin/tags/{$tag->id}", $payload)
        ->assertOk()
        ->assertJsonPath('message', 'Tag updated successfully.')
        ->assertJsonPath('data.name', 'OAuth')
        ->assertJsonPath('data.slug', 'oauth');

    $this->assertDatabaseHas('tags', [
        'id' => $tag->id,
        ...$payload,
    ]);
});

it('soft deletes a tag', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::DeleteTags);

    $tag = Tag::factory()->create();

    $this->actingAs($user, 'api')
        ->deleteJson("/api/admin/tags/{$tag->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Tag deleted successfully.');

    $this->assertSoftDeleted($tag);
});

it('forbids editor without manage permissions from creating tag', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewTags);

    $this->actingAs($user, 'api')
        ->postJson('/api/admin/tags', [
            'name' => 'Passport',
            'slug' => 'passport',
        ])
        ->assertForbidden();

    $this->assertDatabaseMissing('tags', [
        'slug' => 'passport',
    ]);
});

it('requires authentication to access admin tags', function () {
    $this->getJson('/api/admin/tags')
        ->assertUnauthorized();
});
