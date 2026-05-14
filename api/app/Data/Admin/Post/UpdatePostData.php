<?php

namespace App\Data\Admin\Post;

use App\Enums\Post\PostStatusEnum;
use Illuminate\Http\UploadedFile;

final readonly class UpdatePostData
{
    public function __construct(
        public string $title,
        public string $slug,
        public ?UploadedFile $image,
        public ?string $excerpt,
        public string $content,
        public PostStatusEnum $status,
        public array $categoryIds,
        public array $tagIds,
        public bool $isFeatured,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            slug: $data['slug'],
            image: $data['image'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            content: $data['content'],
            status: $data['status'] instanceof PostStatusEnum
                ? $data['status']
                : PostStatusEnum::from($data['status']),
            categoryIds: $data['category_ids'] ?? [],
            tagIds: $data['tag_ids'] ?? [],
            isFeatured: (bool) ($data['is_featured'] ?? false),
        );
    }
}
