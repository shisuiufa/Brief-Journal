<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/**
 * @mixin Post
 */
#[OA\Schema(
    schema: 'PostResource',
    required: ['id', 'title', 'slug', 'content', 'status', 'author'],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'Building APIs with Laravel Passport'),
        new OA\Property(property: 'slug', type: 'string', example: 'building-apis-with-laravel-passport'),
        new OA\Property(property: 'excerpt', type: 'string', nullable: true, example: 'Short post summary.'),
        new OA\Property(property: 'content', type: 'string', example: 'Post body content.'),
        new OA\Property(property: 'image_url', type: 'string', nullable: true, example: '/storage/posts/example.jpg'),
        new OA\Property(property: 'status', type: 'string', enum: ['draft', 'published'], example: 'published'),
        new OA\Property(property: 'published_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'author', ref: '#/components/schemas/UserResource'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ],
    type: 'object'
)]
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'image_url' => asset($this->image),
            'status' => $this->status,
            'published_at' => $this->published_at,
            'author' => new UserResource($this->author),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
