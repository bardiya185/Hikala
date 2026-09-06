<?php

namespace App\Services\Banner;

use App\Models\Banner;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BannerService
{
    public function __construct(
        private BannerImageService $imageService
    ) {}

    /**
     * Get all banners (paginated) - Admin
     */
    public function all(?int $positionId = null, int $perPage = 20): LengthAwarePaginator
    {
        $query = Banner::with('position');

        if ($positionId) {
            $query->where('banner_position_id', $positionId);
        }

        return $query
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    /**
     * Create a new banner
     */
    public function create(array $data, ?UploadedFile $image = null, ?UploadedFile $mobileImage = null): Banner
    {
        return DB::transaction(function () use ($data, $image, $mobileImage) {
            if ($image) {
                $data['image'] = $this->imageService->uploadImage($image);
            }
            if ($mobileImage) {
                $data['mobile_image'] = $this->imageService->uploadMobileImage($mobileImage);
            }

            return Banner::create($data);
        });
    }

    /**
     * Update banner
     */
    public function update(
        Banner $banner,
        array $data,
        ?UploadedFile $image = null,
        ?UploadedFile $mobileImage = null
    ): Banner {
        return DB::transaction(function () use ($banner, $data, $image, $mobileImage) {
            if ($image) {
                $data['image'] = $this->imageService->replaceImage(
                    $image,
                    $banner->image
                );
            }
            if ($mobileImage) {
                $data['mobile_image'] = $this->imageService->replaceMobileImage(
                    $mobileImage,
                    $banner->mobile_image
                );
            }

            $banner->update($data);

            return $banner->fresh();
        });
    }

    /**
     * Delete banner with all its images
     */
    public function delete(Banner $banner): void
    {
        DB::transaction(function () use ($banner) {
            $this->imageService->deleteAllImages($banner);
            $banner->delete();
        });
    }

    /**
     * Track click
     */
    public function trackClick(Banner $banner): void
    {
        $banner->increment('click_count');
    }

    /**
     * Track view
     */
    public function trackView(Banner $banner): void
    {
        $banner->increment('view_count');
    }
}