<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;

class BrandController extends Controller
{
    #[OA\Get(
        path: "/api/brands",
        tags: ["Brands"],
        summary: "List Brands",
        description: "Get all brands.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Brands retrieved successfully."
            )
        ]
    )]    public function index()
    {
        return BrandResource::collection(
            Brand::orderBy('sort_order')->get()
        );
    }

    public function store(StoreBrandRequest $request)
    {
        $brand = Brand::create($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Brand $brand)
    {
        return new BrandResource($brand);
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return new BrandResource($brand);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return response()->json([
            'message' => 'Brand deleted successfully.',
        ]);
    }
}