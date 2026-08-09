<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountCampaignResource;
use App\Http\Resources\ProductResource;
use App\Models\DiscountCampaign;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Campaign Products",
    description: "Products of specific campaigns (Flash Sale, Special Offers, etc.)"
)]
class CampaignProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    // ================================================================
    // 🎯 GET Products by Campaign Slug
    // ================================================================
    #[OA\Get(
        path: '/api/campaigns/{slug}/products',
        operationId: 'campaigns.products',
        tags: ['Campaign Products'],
        summary: 'Get products of a specific campaign',
        description: 'Returns campaign details + its products in one response. Perfect for campaign landing pages.',
        parameters: [
            new OA\Parameter(
                name: 'slug',
                in: 'path',
                required: true,
                description: 'Campaign slug (flash-sale, special, weekly, clearance, ...)',
                schema: new OA\Schema(type: 'string', example: 'flash-sale')
            ),
            new OA\Parameter(
                name: 'category_id',
                in: 'query',
                description: 'Filter products by category',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'brand_id',
                in: 'query',
                description: 'Filter products by brand',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'min_price',
                in: 'query',
                description: 'Minimum price',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'max_price',
                in: 'query',
                description: 'Maximum price',
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'sort_by',
                in: 'query',
                description: 'Sort field',
                schema: new OA\Schema(
                    type: 'string',
                    default: 'created_at',
                    enum: ['base_price', 'view_count', 'created_at', 'title']
                )
            ),
            new OA\Parameter(
                name: 'sort_order',
                in: 'query',
                schema: new OA\Schema(type: 'string', default: 'desc', enum: ['asc', 'desc'])
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 20, maximum: 100)
            ),
            new OA\Parameter(
                name: 'page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Campaign and products retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'campaign',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer'),
                                new OA\Property(property: 'name', type: 'string', example: 'Flash Sale'),
                                new OA\Property(property: 'slug', type: 'string', example: 'flash-sale'),
                                new OA\Property(property: 'description', type: 'string'),
                                new OA\Property(property: 'icon', type: 'string', example: '⚡'),
                                new OA\Property(property: 'color', type: 'string', example: '#DC2626'),
                                new OA\Property(property: 'banner_image', type: 'string', nullable: true),
                                new OA\Property(property: 'starts_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'ends_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'is_currently_active', type: 'boolean'),
                                new OA\Property(property: 'time_remaining_seconds', type: 'integer', example: 3600),
                            ]
                        ),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                        new OA\Property(
                            property: 'meta',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer'),
                                new OA\Property(property: 'last_page', type: 'integer'),
                                new OA\Property(property: 'per_page', type: 'integer'),
                                new OA\Property(property: 'total', type: 'integer'),
                                new OA\Property(property: 'has_more', type: 'boolean'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Campaign not found or inactive')
        ]
    )]
    public function index(Request $request, string $slug)
    {
        // 🔍 پیدا کردن کمپین
        $campaign = DiscountCampaign::where('slug', $slug)
            ->active()
            ->firstOrFail();

        // 📦 گرفتن محصولات کمپین
        $result = $this->productService->listByCampaign($campaign, $request);

        // ⏰ محاسبه زمان باقی‌مانده
        $timeRemaining = $this->calculateTimeRemaining($campaign);

        return response()->json([
            'success' => true,
            'campaign' => [
                'id' => $campaign->id,
                'name' => $campaign->name,
                'slug' => $campaign->slug,
                'description' => $campaign->description,
                'icon' => $campaign->icon,
                'color' => $campaign->color,
                'banner_image' => $campaign->banner_url,
                'priority' => $campaign->priority,
                'starts_at' => $campaign->starts_at,
                'ends_at' => $campaign->ends_at,
                'is_currently_active' => $campaign->isCurrentlyActive(),
                'time_remaining_seconds' => $timeRemaining,
            ],
            'data' => ProductResource::collection($result['data']),
            'meta' => $result['meta'],
        ]);
    }

    // ================================================================
    // 📋 GET All Active Campaigns (Public)
    // ================================================================
    #[OA\Get(
        path: '/api/campaigns',
        operationId: 'campaigns.list',
        tags: ['Campaign Products'],
        summary: 'List all active campaigns',
        description: 'Get all active campaigns sorted by priority. Great for homepage banners.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Campaigns retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(type: 'object')
                        ),
                    ]
                )
            )
        ]
    )]
    public function listActive()
    {
        $campaigns = DiscountCampaign::active()
            ->byPriority()
            ->withCount('discounts')
            ->get();

        return response()->json([
            'success' => true,
            'data' => DiscountCampaignResource::collection($campaigns),
        ]);
    }

    // ================================================================
    // 🛠️ HELPER: Calculate time remaining
    // ================================================================
    private function calculateTimeRemaining(DiscountCampaign $campaign): ?int
    {
        if (!$campaign->ends_at) {
            return null;
        }

        $remaining = now()->diffInSeconds($campaign->ends_at, false);

        return $remaining > 0 ? (int) $remaining : 0;
    }
}