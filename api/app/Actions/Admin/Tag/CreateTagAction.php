<?php

namespace App\Actions\Admin\Tag;

use App\Contracts\Admin\Tag\CreateTagActionInterface;
use App\Data\Admin\Tag\TagData;
use App\Models\Tag;
use Throwable;

final readonly class CreateTagAction implements CreateTagActionInterface
{
    /**
     * @throws Throwable
     */
    public function execute(TagData $data): Tag
    {
        $tag = new Tag([
            'name' => $data->name,
            'slug' => $data->slug,
        ]);

        $tag->saveOrFail();

        return $tag;
    }
}
