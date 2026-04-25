<?php

namespace App\Services\Media;

use App\Contracts\Media\ImageStorageInterface;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\UploadedFile;

final readonly class ImageStorageService implements ImageStorageInterface
{
    public function __construct(
        private FilesystemFactory $filesystem,
    ) {}

    public function store(UploadedFile $file, string $directory = 'images', ?string $disk = null): string
    {
        return $file->store($directory, $this->resolveDisk($disk));
    }

    public function delete(?string $path, ?string $disk = null): void
    {
        if (blank($path)) {
            return;
        }

        $this->filesystem->disk($this->resolveDisk($disk))->delete($path);
    }

    private function resolveDisk(?string $disk): string
    {
        return $disk ?? config('filesystems.default');
    }
}
