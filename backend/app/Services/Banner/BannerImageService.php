<?php

namespace App\Services\Banner;

use App\Models\Banner;
use App\Services\Image\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BannerImageService
{
    public function __construct(
        private ImageService $imageService
    ) {}

    /**
     * Upload banner image (desktop)
     */
    public function uploadImage(UploadedFile $file): string
    {
        return $this->imageService->upload($file, 'banners');
    }

    /**
     * Upload banner mobile image
     */
    public function uploadMobileImage(UploadedFile $file): string
    {
        return $this->imageService->upload($file, 'banners/mobile');
    }

    /**
     * Replace existing image
     */
    public function replaceImage(UploadedFile $newFile, ?string $oldPath): string
    {
        if ($oldPath) {
            $this->imageService->delete($oldPath);
        }
        return $this->uploadImage($newFile);
    }

    /**
     * Replace mobile image
     */
    public function replaceMobileImage(UploadedFile $newFile, ?string $oldPath): string
    {
        if ($oldPath) {
            $this->imageService->delete($oldPath);
        }
        
        return $this->uploadMobileImage($newFile);
    }

    /**
     * Delete all images of a banner
     */
    public function deleteAllImages(Banner $banner): void
    {
        if ($banner->image) {
            $this->imageService->delete($banner->image);
        }

        if ($banner->mobile_image) {
            $this->imageService->delete($banner->mobile_image);
        }
    }
}