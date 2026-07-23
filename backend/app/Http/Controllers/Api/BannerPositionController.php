<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerPositionResource;
use App\Models\BannerPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class BannerPositionController extends Controller
{
    // ================================================================
    // 🔒 ADMIN: List All Positions
    // ================================================================
    #[OA\Get(
        path: '/api/admin/banner-positions',
        tags: ['Banners'],
        summary: 'List all banner positions',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Positions list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/BannerPosition')
                        ),
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $positions = BannerPosition::withCount('banners')->get();

        return response()->json([
            'success' => true,
            'data' => BannerPositionResource::collection($positions),
        ]);
    }

    // ================================================================
    // 🔒 ADMIN: Create Position
    // ================================================================
    #[OA\Post(
        path: '/api/admin/banner-positions',
        tags: ['Banners'],
        summary: 'Create new banner position',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['key', 'name'],
                properties: [
                    new OA\Property(property: 'key', type: 'string', example: 'home_middle_4'),
                    new OA\Property(property: 'name', type: 'string', example: '۴ بنر وسط صفحه اصلی'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'max_banners', type: 'integer', example: 4),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Position created'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'key' => 'required|string|max:255|unique:banner_positions,key',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_banners' => 'nullable|integer|min:1|max:20',
            'is_active' => 'boolean',
        ]);

        $position = BannerPosition::create($data);

        return (new BannerPositionResource($position))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // 🔒 ADMIN: Show Position
    // ================================================================
    #[OA\Get(
        path: '/api/admin/banner-positions/{position}',
        tags: ['Banners'],
        summary: 'Show position details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'position',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Position details'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(BannerPosition $position)
    {
        return new BannerPositionResource($position->load('banners'));
    }

    // ================================================================
    // 🔒 ADMIN: Update Position
    // ================================================================
    #[OA\Put(
        path: '/api/admin/banner-positions/{position}',
        tags: ['Banners'],
        summary: 'Update banner position',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'position',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'key', type: 'string'),
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'max_banners', type: 'integer'),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Position updated'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function update(Request $request, BannerPosition $position)
    {
        $data = $request->validate([
            'key' => 'sometimes|string|max:255|unique:banner_positions,key,' . $position->id,
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'max_banners' => 'nullable|integer|min:1|max:20',
            'is_active' => 'boolean',
        ]);

        $position->update($data);

        return new BannerPositionResource($position);
    }

    // ================================================================
    // 🔒 ADMIN: Delete Position
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/banner-positions/{position}',
        tags: ['Banners'],
        summary: 'Delete banner position',
        description: 'Deletes position and all its banners',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'position',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Position deleted'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function destroy(BannerPosition $position)
    {
        // بنرها با cascade delete پاک میشن
        $position->delete();

        return response()->json([
            'success' => true,
            'message' => 'Position deleted successfully',
        ]);
    }
}