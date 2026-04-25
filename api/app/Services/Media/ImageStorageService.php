<?php

namespace App\Services\Media;

use App\Contracts\Media\ImageStorageInterface;
use Illuminate\Contracts\Filesystem\Factory as FilesystemFactory;
use Illuminate\Http\UploadedFile;
use Throwable;

final readonly class ImageStorageService implements ImageStorageInterface
{
    public function __construct(
        private FilesystemFactory $filesystem,
    ) {}

    public function store(UploadedFile $file, string $directory = 'images', ?string $disk = null): string
    {
        return $file->store($directory, $this->resolveDisk($disk));
    }

    /**
     * @throws Throwable
     */
    public function replace(
        ?string $currentPath,
        UploadedFile $newFile,
        string $directory = 'images',
        ?string $disk = null,
    ): string {
        $storageDisk = $this->resolveDisk($disk);
        $newPath = $this->store($newFile, $directory, $storageDisk);

        try {
            $this->delete($currentPath, $storageDisk);

            return $newPath;
        } catch (Throwable $exception) {
            $this->filesystem->disk($storageDisk)->delete($newPath);

            throw $exception;
        }
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
