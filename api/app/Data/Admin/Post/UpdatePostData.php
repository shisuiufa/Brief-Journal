<?php

namespace App\Data\Admin\Post;

use App\Enums\Post\PostStatusEnum;
use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;

final readonly class UpdatePostData
{
    public function __construct(
        public ?string $title,
        public ?string $slug,
        public ?UploadedFile $image,
        public ?string $excerpt,
        public ?string $content,
        public ?PostStatusEnum $status,
        public ?CarbonImmutable $publishedAt,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? null,
            slug: $data['slug'] ?? null,
            image: $data['image'] ?? null,
            excerpt: $data['excerpt'] ?? null,
            content: $data['content'] ?? null,
            status: isset($data['status'])
                ? PostStatusEnum::from($data['status'])
                : null,
            publishedAt: isset($data['published_at'])
                ? CarbonImmutable::parse($data['published_at'])
                : null,
        );
    }
}
