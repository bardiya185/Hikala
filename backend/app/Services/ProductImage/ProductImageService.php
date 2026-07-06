<?php

namespace App\Services\ProductImage;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function upload(Product $product, UploadedFile $image, ?string $alt = null): ProductImage
    {
        return DB::transaction(function () use ($product, $image, $alt) {

            $path = $image->store('products', 'public');

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

    public function delete(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->image_path);

        $image->delete();
    }
}