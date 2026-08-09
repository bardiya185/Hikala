<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerPositionResource;
use App\Models\Banner;
use App\Models\BannerPosition;
use App\Services\Banner\BannerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Banners",
    description: "Banner management and display"
)]
class BannerController extends Controller
{
    public function __construct(
        private BannerService $bannerService
    ) {}

    // ================================================================
    // 🌐 PUBLIC: All Banners Grouped by Position
    // ================================================================
    #[OA\Get(
        path: '/api/banners',
        operationId: 'banners.public.all',
        tags: ['Banners'],
        summary: 'Get all active banners grouped by position',
        description: 'Returns all active banner positions with their banners',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Banners retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            )
        ]
    )]
    public function all()
    {
        $positions = BannerPosition::where('is_active', true)
            ->with('activeBanners.linkable')
            ->get();

        return response()->json([
            'success' => true,
            'data' => BannerPositionResource::collection($positions),
        ]);
    }

    // ================================================================
    // 🌐 PUBLIC: Get Banners by Position Key
    // ================================================================
    #[OA\Get(
        path: '/api/banners/position/{key}',
        operationId: 'banners.public.byPosition',
        tags: ['Banners'],
        summary: 'Get banners of a specific position',
        description: 'Returns active banners of a position by its key',
        parameters: [
            new OA\Parameter(
                name: 'key',
                in: 'path',
                required: true,
                description: 'Position key (e.g., home_middle_4)',
                schema: new OA\Schema(type: 'string', example: 'home_middle_4')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Banners retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Position not found')
        ]
    )]
    public function byPosition(string $key)
    {
        $position = BannerPosition::where('key', $key)
            ->where('is_active', true)
            ->with('activeBanners.linkable')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new BannerPositionResource($position),
        ]);
    }

    // ================================================================
    // 🌐 PUBLIC: Track Banner Click
    // ================================================================
    #[OA\Post(
        path: '/api/banners/{banner}/click',
        operationId: 'banners.public.trackClick',
        tags: ['Banners'],
        summary: 'Track banner click',
        description: 'Increment click counter and return banner URL',
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                description: 'Banner ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Click tracked successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'url', type: 'string', example: '/category/5'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Banner not found')
        ]
    )]
    public function trackClick(Banner $banner)
    {
        $this->bannerService->trackClick($banner);

        return response()->json([
            'success' => true,
            'url' => $banner->url,
        ]);
    }

    // ================================================================
    // 🔒 ADMIN: List All Banners (with pagination)
    // ================================================================
    #[OA\Get(
        path: '/api/admin/banners',
        operationId: 'banners.admin.index',
        tags: ['Banners'],
        summary: 'List all banners (admin)',
        description: 'Get all banners with pagination for admin panel',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'position_id',
                in: 'query',
                required: false,
                description: 'Filter by position ID',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                required: false,
                description: 'Items per page (max 100)',
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Banners retrieved successfully'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function adminIndex(Request $request)
    {
        $banners = $this->bannerService->all(
            $request->get('position_id'),
            min($request->get('per_page', 20), 100)
        );

        return BannerResource::collection($banners);
    }

    // ================================================================
    // 🔒 ADMIN: Create Banner
    // ================================================================
    #[OA\Post(
        path: '/api/admin/banners',
        operationId: 'banners.admin.store',
        tags: ['Banners'],
        summary: 'Create new banner',
        description: 'Upload and create a new banner',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['banner_position_id', 'image'],
                    properties: [
                        new OA\Property(property: 'banner_position_id', type: 'integer', example: 1),
                        new OA\Property(property: 'title', type: 'string', nullable: true, example: 'Mobile Mania'),
                        new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: 'Up to 25% off'),
                        new OA\Property(property: 'image', type: 'string', format: 'binary'),
                        new OA\Property(property: 'mobile_image', type: 'string', format: 'binary', nullable: true),
                        new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                        new OA\Property(
                            property: 'linkable_type',
                            type: 'string',
                            enum: ['App\\Models\\Product', 'App\\Models\\Category', 'App\\Models\\Brand'],
                            nullable: true
                        ),
                        new OA\Property(property: 'linkable_id', type: 'integer', nullable: true),
                        new OA\Property(property: 'custom_url', type: 'string', nullable: true, example: '/products?category_id=1'),
                        new OA\Property(property: 'background_color', type: 'string', nullable: true, example: '#DC2626'),
                        new OA\Property(property: 'text_color', type: 'string', nullable: true, example: '#FFFFFF'),
                        new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
                        new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
                        new OA\Property(property: 'sort_order', type: 'integer', example: 0),
                        new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Banner created successfully'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function store(StoreBannerRequest $request)
    {
        $banner = $this->bannerService->create(
            $request->validated(),
            $request->file('image'),
            $request->file('mobile_image')
        );

        return (new BannerResource($banner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // 🔒 ADMIN: Show Single Banner
    // ================================================================
    #[OA\Get(
        path: '/api/admin/banners/{banner}',
        operationId: 'banners.admin.show',
        tags: ['Banners'],
        summary: 'Show banner details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                description: 'Banner ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Banner details'),
            new OA\Response(response: 404, description: 'Banner not found')
        ]
    )]
    public function show(Banner $banner)
    {
        return new BannerResource($banner->load('position', 'linkable'));
    }

    // ================================================================
    // 🔒 ADMIN: Update Banner
    // ================================================================
    #[OA\Post(
        path: '/api/admin/banners/{banner}',
        operationId: 'banners.admin.update',
        tags: ['Banners'],
        summary: 'Update banner',
        description: 'Update banner details (use POST with _method=PUT for file upload)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                description: 'Banner ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: '_method', type: 'string', example: 'PUT'),
                        new OA\Property(property: 'banner_position_id', type: 'integer'),
                        new OA\Property(property: 'title', type: 'string', nullable: true),
                        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
                        new OA\Property(property: 'image', type: 'string', format: 'binary', nullable: true),
                        new OA\Property(property: 'mobile_image', type: 'string', format: 'binary', nullable: true),
                        new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                        new OA\Property(property: 'linkable_type', type: 'string', nullable: true),
                        new OA\Property(property: 'linkable_id', type: 'integer', nullable: true),
                        new OA\Property(property: 'custom_url', type: 'string', nullable: true),
                        new OA\Property(property: 'background_color', type: 'string', nullable: true),
                        new OA\Property(property: 'text_color', type: 'string', nullable: true),
                        new OA\Property(property: 'sort_order', type: 'integer'),
                        new OA\Property(property: 'is_active', type: 'boolean'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Banner updated successfully'),
            new OA\Response(response: 404, description: 'Banner not found'),
            new OA\Response(response: 422, description: 'Validation error')
        ]
    )]
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner = $this->bannerService->update(
            $banner,
            $request->validated(),
            $request->file('image'),
            $request->file('mobile_image')
        );

        return new BannerResource($banner);
    }

    // ================================================================
    // 🔒 ADMIN: Delete Banner
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/banners/{banner}',
        operationId: 'banners.admin.destroy',
        tags: ['Banners'],
        summary: 'Delete banner',
        description: 'Delete a banner and its images',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                description: 'Banner ID',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Banner deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Banner deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Banner not found')
        ]
    )]
    public function destroy(Banner $banner)
    {
        $this->bannerService->delete($banner);

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }
}