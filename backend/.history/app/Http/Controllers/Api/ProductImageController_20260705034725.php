<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImage\ProductImageService;

class ProductImageController extends Controller
{
    public function __construct(
        private ProductImageService $service
    ) {}
}