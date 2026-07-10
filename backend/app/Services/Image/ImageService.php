<?php

namespace App\Services\ProductImage;

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
     * آپلود عکس جدید
     */
    public function upload(Product $product, UploadedFile $file, ?string $alt = null): ProductImage
    {
        return DB::transaction(function () use ($product, $file, $alt) {
            // آپلود با استفاده از ImageService
            $path = $this->imageService->upload($file, 'products/' . $product->id);

            // ذخیره در دیتابیس
            return ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'alt' => $alt,
                'sort_order' => ProductImage::where('product_id', $product->id)->count() + 1,
                'is_main' => false,
            ]);
        });
    }

    /**
     * حذف عکس
     */
    public function delete(ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            // حذف فایل با استفاده از ImageService
            $this->imageService->delete($image->path);
            
            // حذف از دیتابیس
            $image->delete();
        });
    }

    /**
     * تنظیم عکس به عنوان اصلی
     */
    public function setMain(ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            // همه عکس‌های این محصول رو غیراصلی کن
            ProductImage::where('product_id', $image->product_id)
                ->update(['is_main' => false]);

            // این عکس رو اصلی کن
            $image->update(['is_main' => true]);
        });
    }

    /**
     * مرتب‌سازی عکس‌ها
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
     * آپلود عکس با تنظیم به عنوان اصلی
     */
    public function uploadWithMain(Product $product, UploadedFile $file, ?string $alt = null): ProductImage
    {
        $image = $this->upload($product, $file, $alt);
        $this->setMain($image);
        
        return $image;
    }

    /**
     * دریافت عکس اصلی محصول
     */
    public function getMainImage(Product $product): ?ProductImage
    {
        return $product->images()->where('is_main', true)->first();
    }

    /**
     * دریافت همه عکس‌های محصول با مرتب‌سازی
     */
    public function getImages(Product $product)
    {
        return $product->images()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}