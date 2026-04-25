<?php

namespace App\Data\Admin\Post;

use App\Enums\Post\PostStatusEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;

final readonly class CreatePostData
{
    public function __construct(
        public int $userId,
        public string $title,
        public string $slug,
        public UploadedFile $image,
        public ?string $excerpt,
        public string $content,
        public PostStatusEnum $status,
        public ?CarbonImmutable $publishedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            title: $data['title'],
            slug: $data['slug'],
            image: $data['image'],
            excerpt: $data['excerpt'] ?? null,
            content: $data['content'],
            status: $data['status'] instanceof PostStatusEnum
                ? $data['status']
                : PostStatusEnum::from($data['status']),
            publishedAt: isset($data['published_at'])
                ? CarbonImmutable::parse($data['published_at'])
                : null,
        );
    }
}
