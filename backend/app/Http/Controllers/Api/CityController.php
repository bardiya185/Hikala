<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use OpenApi\Attributes as OA;

class CityController extends Controller
{
    #[OA\Get(
        path: "/api/cities",
        tags: ["Cities"],
        summary: "Get cities by province",
        description: "Get all cities of a specific province. Use this for dropdowns and address selection.",
        parameters: [
            new OA\Parameter(
                name: "province_id",
                in: "query",
                required: true,
                description: "Province ID to filter cities",
                schema: new OA\Schema(type: "integer", example: 8)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Cities retrieved successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "province_id", type: "integer", example: 8),
                                    new OA\Property(property: "name", type: "string", example: "Tehran"),
                                    new OA\Property(property: "slug", type: "string", example: "tehran"),
                                    new OA\Property(property: "code", type: "string", nullable: true, example: null),
                                    new OA\Property(property: "is_active", type: "boolean", example: true),
                                    new OA\Property(property: "sort_order", type: "integer", example: 1),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error - province_id is required",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The province id field is required."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        return CityResource::collection(
            City::query()
                ->where('province_id', request('province_id'))
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
        );
    }
}