<?php

namespace App\Services\Product\ProductImage;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\Image\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ProductImageService
{
    public function __construct(
        private ImageService $imageService
    ) {}

    /**
     * Upload a new image
     */
    public function upload(Product $product, UploadedFile $image, ?string $alt = null): ProductImage
    {
        return DB::transaction(function () use ($product, $image, $alt) {
            $path = $this->imageService->upload($image, 'products/' . $product->id);

            $isMain = !$product->images()->exists();

            return ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'alt' => $alt,
                'sort_order' => $product->images()->count() + 1,
                'is_main' => $isMain,
            ]);
        });
    }

    /**
     * Delete an image
     */
    public function delete(ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            $this->imageService->delete($image->image_path);
            $image->delete();
        });
    }

    /**
     * Set an image as main
     */
    public function setMain(ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            ProductImage::where('product_id', $image->product_id)
                ->update(['is_main' => false]);

            $image->update(['is_main' => true]);
        });
    }

    /**
     * Reorder images
     */
    public function reorder(array $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order as $index => $imageId) {
                ProductImage::where('id', $imageId)
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    /**
     * Get all images of a product with sorting
     */
    public function getImages(Product $product)
    {
        return $product->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get the main image of a product
     */
    public function getMainImage(Product $product): ?ProductImage
    {
        return $product->images()->where('is_main', true)->first();
    }
}