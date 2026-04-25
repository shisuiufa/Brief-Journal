<?php

namespace App\Contracts\Media;

use Illuminate\Http\UploadedFile;

interface ImageStorageInterface
{
    public function store(UploadedFile $file, string $directory = 'images', ?string $disk = null): string;

    public function delete(?string $path, ?string $disk = null): void;
}
