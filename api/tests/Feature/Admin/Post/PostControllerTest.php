<?php

use App\Contracts\Admin\Post\CreatePostActionInterface;
use App\Contracts\Admin\Post\UpdatePostActionInterface;
use App\Data\Admin\Post\CreatePostData;
use App\Data\Admin\Post\UpdatePostData;
use App\Enums\Access\PermissionEnum;
use App\Enums\Access\RoleEnum;
use App\Enums\Post\PostStatusEnum;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;

use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PermissionsSeeder::class);
    $this->seed(RolesSeeder::class);
});

$postPayload = function (array $overrides = []): array {
    return array_merge([
        'title' => 'test post',
        'slug' => 'test-post',
        'image' => UploadedFile::fake()->create('post.jpg'),
        'excerpt' => 'short',
        'content' => 'full content',
        'status' => PostStatusEnum::Draft->value,
    ], $overrides);
};

$updatePostPayload = function (array $overrides = []): array {
    return array_merge([
        'title' => 'updated post',
        'slug' => 'updated-post',
        'image' => UploadedFile::fake()->create('new-post.jpg'),
        'excerpt' => 'updated short',
        'content' => 'updated content',
        'status' => PostStatusEnum::Published->value,
    ], $overrides);
};

it('returns paginated posts list', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewPosts);

    Post::factory()
        ->count(2)
        ->for(User::factory(), 'author')
        ->create();

    $this->actingAs($user)
        ->getJson('/api/admin/posts')
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'meta',
            'links',
        ])
        ->assertJsonCount(2, 'data');
});

it('creates a post and returns a response', function () use ($postPayload) {
    $user = createUserWithRoleAndPermission(PermissionEnum::CreatePosts);
    $payload = $postPayload();

    $post = Post::factory()
        ->for($user, 'author')
        ->make([
            'title' => $payload['title'],
            'slug' => $payload['slug'],
            'excerpt' => $payload['excerpt'],
            'content' => $payload['content'],
            'status' => PostStatusEnum::from($payload['status']),
        ]);

    mock(CreatePostActionInterface::class, function (MockInterface $mock) use ($payload, $user, $post) {
        $mock->shouldReceive('execute')
            ->once()
            ->withArgs(function (CreatePostData $data) use ($user, $payload) {
                return $data->title === $payload['title']
                    && $data->slug === $payload['slug']
                    && $data->excerpt === $payload['excerpt']
                    && $data->content === $payload['content']
                    && $data->userId === $user->id
                    && $data->status === PostStatusEnum::Draft
                    && $data->image instanceof UploadedFile;
            })
            ->andReturn($post);
    });

    $this->actingAs($user)
        ->post('/api/admin/posts', $payload, ['Accept' => 'application/json'])
        ->assertCreated()
        ->assertJsonPath('message', 'Post created successfully.')
        ->assertJsonPath('data.title', $payload['title'])
        ->assertJsonPath('data.slug', $payload['slug'])
        ->assertJsonPath('data.status', $payload['status']);
});

it('shows a post', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::ViewPosts);

    $post = Post::factory()
        ->for(User::factory(), 'author')
        ->create();

    $this->actingAs($user)
        ->getJson("/api/admin/posts/{$post->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $post->id)
        ->assertJsonPath('data.title', $post->title)
        ->assertJsonPath('data.slug', $post->slug);
});

it('updates a post and returns a response', function () use ($postPayload, $updatePostPayload) {
    $user = createUserWithRoleAndPermission(PermissionEnum::EditPosts);
    $payload = $postPayload();
    $updatePayload = $updatePostPayload();

    $post = Post::factory()
        ->for($user, 'author')
        ->create([
            'title' => $payload['title'],
            'slug' => $payload['slug'],
            'excerpt' => $payload['excerpt'],
            'content' => $payload['content'],
            'status' => PostStatusEnum::from($payload['status']),
        ]);

    $updatedPost = Post::factory()
        ->for($user, 'author')
        ->make([
            'id' => $post->id,
            'title' => $updatePayload['title'],
            'slug' => $updatePayload['slug'],
            'excerpt' => $updatePayload['excerpt'],
            'content' => $updatePayload['content'],
            'status' => PostStatusEnum::Published,
        ]);

    mock(UpdatePostActionInterface::class, function (MockInterface $mock) use ($post, $updatedPost, $updatePayload) {
        $mock->shouldReceive('execute')
            ->once()
            ->withArgs(function (Post $postArg, UpdatePostData $data) use ($post, $updatePayload) {
                return $postArg->is($post)
                    && $data->title === $updatePayload['title']
                    && $data->slug === $updatePayload['slug']
                    && $data->excerpt === $updatePayload['excerpt']
                    && $data->content === $updatePayload['content']
                    && $data->status === PostStatusEnum::Published
                    && $data->image instanceof UploadedFile;
            })
            ->andReturn($updatedPost);
    });

    $this->actingAs($user)
        ->post("/api/admin/posts/{$post->id}", [
            ...$updatePayload,
            '_method' => 'PUT',
        ], ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonPath('message', 'Post updated successfully.')
        ->assertJsonPath('data.title', $updatePayload['title'])
        ->assertJsonPath('data.status', $updatePayload['status'])
        ->assertJsonPath('data.slug', $updatePayload['slug']);
});

it('soft deletes a post', function () {
    $user = createUserWithRoleAndPermission(PermissionEnum::DeletePosts);

    $post = Post::factory()
        ->for(User::factory(), 'author')
        ->create();

    $this->actingAs($user)
        ->deleteJson("/api/admin/posts/{$post->id}")
        ->assertOk()
        ->assertJsonPath('message', 'Post deleted successfully.');

    $this->assertSoftDeleted($post);
});

it('forbids users without permission from reading and deleting posts', function (
    string $method,
    string $uri,
    PermissionEnum $permission,
) {
    $role = Role::findByName(RoleEnum::Editor->value);
    $role->revokePermissionTo($permission->value);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = createUserWithRole(RoleEnum::Editor);

    $post = Post::factory()
        ->for(User::factory(), 'author')
        ->create();

    $uri = str_replace('{post}', (string) $post->id, $uri);

    $this->actingAs($user)
        ->json($method, $uri)
        ->assertForbidden();
})->with([
    'index' => ['GET', '/api/admin/posts', PermissionEnum::ViewPosts],
    'show' => ['GET', '/api/admin/posts/{post}', PermissionEnum::ViewPosts],
    'destroy' => ['DELETE', '/api/admin/posts/{post}', PermissionEnum::DeletePosts],
]);

it('forbids users without permission from creating posts', function () use ($postPayload) {
    $role = Role::findByName(RoleEnum::Editor->value);
    $role->revokePermissionTo(PermissionEnum::CreatePosts->value);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = createUserWithRole(RoleEnum::Editor);

    mock(CreatePostActionInterface::class, function (MockInterface $mock) {
        $mock->shouldNotReceive('execute');
    });

    $this->actingAs($user)
        ->post('/api/admin/posts', $postPayload(), ['Accept' => 'application/json'])
        ->assertForbidden();

    $this->assertDatabaseMissing('posts', [
        'slug' => 'test-post',
    ]);
});

it('forbids users without permission from updating posts', function () use ($updatePostPayload) {
    $role = Role::findByName(RoleEnum::Editor->value);
    $role->revokePermissionTo(PermissionEnum::EditPosts->value);

    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $user = createUserWithRole(RoleEnum::Editor);
    $user->permissions()->detach();

    $post = Post::factory()
        ->for(User::factory(), 'author')
        ->create([
            'title' => 'original title',
            'slug' => 'original-slug',
            'status' => PostStatusEnum::Draft,
        ]);

    mock(UpdatePostActionInterface::class, function (MockInterface $mock) {
        $mock->shouldNotReceive('execute');
    });

    $this->actingAs($user)
        ->post("/api/admin/posts/{$post->id}", [
            ...$updatePostPayload(),
            '_method' => 'PUT',
        ], ['Accept' => 'application/json'])
        ->assertForbidden();

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'original title',
        'slug' => 'original-slug',
    ]);
});

it('requires authentication to access admin posts', function () {
    $this->getJson('/api/admin/posts')
        ->assertUnauthorized();
});


