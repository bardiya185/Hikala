<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImage\ProductImageService;

class ProductImageController extends Controller
dd($request->all());
{
    public function __construct(
        private ProductImageService $service
        ) {}
        
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $image = $this->service->upload(
            $product,
            $request->file('image'),
            $request->input('alt')
        );
    
        return (new ProductImageResource($image))
            ->response()
            ->setStatusCode(201);
    }

    public function destroy(ProductImage $image)
{
    $this->service->delete($image);

    return response()->json([
        'message' => 'Image deleted successfully.'
    ]);
}

}