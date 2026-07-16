<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use OpenApi\Attributes as OA;

class ProvinceController extends Controller
{
    #[OA\Get(
        path: "/api/provinces",
        tags: ["Provinces"],
        summary: "Get all provinces",
        description: "Retrieve a list of all active provinces. Use this for dropdowns and address selection forms.",
        responses: [
            new OA\Response(
                response: 200,
                description: "Provinces retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Tehran"),
                                    new OA\Property(property: "slug", type: "string", example: "tehran"),
                                    new OA\Property(property: "code", type: "string", nullable: true, example: "08"),
                                    new OA\Property(property: "country_code", type: "string", example: "IR"),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2024-01-01T10:00:00.000000Z"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2024-01-01T10:00:00.000000Z"),
                                ]
                            )
                        )
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        return ProvinceResource::collection(
            Province::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }
}