<?php

namespace App\Actions\Admin\Tag;

use App\Contracts\Admin\Tag\UpdateTagActionInterface;
use App\Data\Admin\Tag\TagData;
use App\Models\Tag;
use Throwable;

final readonly class UpdateTagAction implements UpdateTagActionInterface
{
    /**
     * @throws Throwable
     */
    public function execute(Tag $tag, TagData $data): Tag
    {
        $tag->updateOrFail([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        return $tag->refresh();
    }
}
