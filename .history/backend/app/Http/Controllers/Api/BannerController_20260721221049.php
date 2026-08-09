<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerPositionResource;
use App\Models\Banner;
use App\Models\BannerPosition;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Banners",
    description: "Banner management and display"
)]
#[OA\Schema(
    schema: "Banner",
    title: "Banner",
    description: "Banner model",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "title", type: "string", nullable: true, example: "همه چیز برای کودک"),
        new OA\Property(property: "subtitle", type: "string", nullable: true, example: "تا ۵۰٪ تخفیف"),
        new OA\Property(property: "image", type: "string", example: "https://example.com/storage/banners/1.jpg"),
        new OA\Property(property: "mobile_image", type: "string", nullable: true),
        new OA\Property(property: "alt_text", type: "string", nullable: true),
        new OA\Property(property: "url", type: "string", nullable: true, example: "/category/5"),
        new OA\Property(property: "background_color", type: "string", nullable: true, example: "#E53E3E"),
        new OA\Property(property: "text_color", type: "string", nullable: true, example: "#FFFFFF"),
        new OA\Property(property: "sort_order", type: "integer", example: 0),
    ]
)]
#[OA\Schema(
    schema: "BannerPosition",
    title: "BannerPosition",
    description: "Banner Position model",
    properties: [
        new OA\Property(property: "key", type: "string", example: "home_middle_4"),
        new OA\Property(property: "name", type: "string", example: "۴ بنر وسط صفحه اصلی"),
        new OA\Property(
            property: "banners",
            type: "array",
            items: new OA\Items(ref: "#/components/schemas/Banner")
        ),
    ]
)]
class BannerController extends Controller
{
    // ================================================================
    // 🌐 PUBLIC: All Banners Grouped by Position
    // ================================================================
    #[OA\Get(
        path: '/api/banners',
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
    public function all()
    {
        $positions = BannerPosition::where('is_active', true)
            ->with('activeBanners')
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
        tags: ['Banners'],
        summary: 'Get banners of a specific position',
        description: 'Returns active banners of a position by key',
        parameters: [
            new OA\Parameter(
                name: 'key',
                in: 'path',
                required: true,
                description: 'Position key',
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
                        new OA\Property(property: 'data', ref: '#/components/schemas/BannerPosition')
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
            ->with('activeBanners')
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
        tags: ['Banners'],
        summary: 'Track banner click',
        description: 'Increment click counter and return banner URL',
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Click tracked',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'url', type: 'string', example: '/category/5'),
                    ]
                )
            )
        ]
    )]
    public function trackClick(Banner $banner)
    {
        $banner->increment('click_count');

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
        tags: ['Banners'],
        summary: 'List all banners (admin)',
        description: 'Get all banners with pagination for admin panel',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'position_id',
                in: 'query',
                description: 'Filter by position',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Banners retrieved successfully'
            ),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function adminIndex(Request $request)
    {
        $query = Banner::with('position');

        if ($request->has('position_id')) {
            $query->where('banner_position_id', $request->position_id);
        }

        $perPage = min($request->get('per_page', 20), 100);
        $banners = $query->orderBy('sort_order')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => BannerResource::collection($banners),
            'meta' => [
                'current_page' => $banners->currentPage(),
                'last_page' => $banners->lastPage(),
                'per_page' => $banners->perPage(),
                'total' => $banners->total(),
            ],
        ]);
    }

    // ================================================================
    // 🔒 ADMIN: Create Banner
    // ================================================================
    #[OA\Post(
        path: '/api/admin/banners',
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
                        new OA\Property(property: 'title', type: 'string', nullable: true),
                        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
                        new OA\Property(property: 'image', type: 'string', format: 'binary'),
                        new OA\Property(property: 'mobile_image', type: 'string', format: 'binary', nullable: true),
                        new OA\Property(property: 'alt_text', type: 'string', nullable: true),
                        new OA\Property(property: 'linkable_type', type: 'string', enum: ['App\\Models\\Product', 'App\\Models\\Category', 'App\\Models\\Brand'], nullable: true),
                        new OA\Property(property: 'linkable_id', type: 'integer', nullable: true),
                        new OA\Property(property: 'custom_url', type: 'string', nullable: true),
                        new OA\Property(property: 'background_color', type: 'string', nullable: true, example: '#E53E3E'),
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
            new OA\Response(response: 201, description: 'Banner created'),
            new OA\Response(response: 422, description: 'Validation error'),
            new OA\Response(response: 401, description: 'Unauthenticated')
        ]
    )]
    public function store(Request $request)
    {
        $data = $request->validate([
            'banner_position_id' => 'required|exists:banner_positions,id',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'linkable_type' => 'nullable|string|in:App\Models\Product,App\Models\Category,App\Models\Brand',
            'linkable_id' => 'nullable|integer',
            'custom_url' => 'nullable|url',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner = Banner::create($data);

        return (new BannerResource($banner))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // 🔒 ADMIN: Show Single Banner
    // ================================================================
    #[OA\Get(
        path: '/api/admin/banners/{banner}',
        tags: ['Banners'],
        summary: 'Show banner details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Banner details'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function show(Banner $banner)
    {
        return new BannerResource($banner->load('position'));
    }

    // ================================================================
    // 🔒 ADMIN: Update Banner
    // ================================================================
    #[OA\Post(
        path: '/api/admin/banners/{banner}',
        tags: ['Banners'],
        summary: 'Update banner (use POST with _method=PUT for file upload)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
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
                        new OA\Property(property: 'image', type: 'string', format: 'binary', nullable: true),
                        new OA\Property(property: 'is_active', type: 'boolean'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Banner updated'),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'banner_position_id' => 'sometimes|exists:banner_positions,id',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'mobile_image' => 'nullable|image|max:2048',
            'alt_text' => 'nullable|string|max:255',
            'linkable_type' => 'nullable|string|in:App\Models\Product,App\Models\Category,App\Models\Brand',
            'linkable_id' => 'nullable|integer',
            'custom_url' => 'nullable|url',
            'background_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // آپلود تصویر جدید و حذف قبلی
        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) {
                Storage::disk('public')->delete($banner->mobile_image);
            }
            $data['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($data);

        return new BannerResource($banner);
    }

    // ================================================================
    // 🔒 ADMIN: Delete Banner
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/banners/{banner}',
        tags: ['Banners'],
        summary: 'Delete banner',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'banner',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Banner deleted',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Banner deleted successfully'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Not found')
        ]
    )]
    public function destroy(Banner $banner)
    {
        // حذف تصاویر از storage
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        if ($banner->mobile_image) {
            Storage::disk('public')->delete($banner->mobile_image);
        }

        $banner->delete();

        return response()->json([
            'success' => true,
            'message' => 'Banner deleted successfully',
        ]);
    }
}