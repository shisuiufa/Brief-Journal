<?php

namespace App\Models;

use App\Enums\Post\PostStatusEnum;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperPost
 */
#[Fillable(['user_id',
    'title',
    'slug',
    'image',
    'excerpt',
    'content',
    'status',
    'published_at',
])]
class Post extends Model
{
    /** @use HasFactory<PostFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => PostStatusEnum::class,
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    #[Scope]
    protected function search(Builder $query, ?string $search): void
    {
        $search = trim((string) $search);

        if ($search === '') {
            return;
        }

        $query->where(function (Builder $query) use ($search): void {
            $query
                ->where('title', 'ILIKE', "%{$search}%")
                ->orWhere('excerpt', 'ILIKE', "%{$search}%");
        });
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query
            ->where('status', PostStatusEnum::Published)
            ->whereNotNull('published_at');
    }
}
