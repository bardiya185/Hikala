<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCampaignRequest;
use App\Http\Requests\UpdateDiscountCampaignRequest;
use App\Http\Resources\DiscountCampaignResource;
use App\Http\Resources\ProductResource;
use App\Models\DiscountCampaign;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Discount Campaigns",
    description: "Manage discount campaigns"
)]
class DiscountCampaignController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}
    #[OA\Get(
        path: '/api/campaigns',
        operationId: 'campaigns.public.index',
        tags: ['Discount Campaigns'],
        summary: 'Get all active campaigns',
        description: 'Returns all active campaigns sorted by priority',
        responses: [
            new OA\Response(response: 200, description: 'Campaigns list'),
        ]
    )]
    public function index()
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
    #[OA\Get(
        path: '/api/campaigns/{slug}',
        operationId: 'campaigns.public.showBySlug',
        tags: ['Discount Campaigns'],
        summary: 'Get campaign details by slug',
        parameters: [
            new OA\Parameter(
                name: 'slug',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string', example: 'flash-sale')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Campaign details'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function showBySlug(string $slug)
    {
        $campaign = DiscountCampaign::where('slug', $slug)
            ->active()
            ->withCount('discounts')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new DiscountCampaignResource($campaign),
        ]);
    }
    #[OA\Get(
        path: '/api/campaigns/{slug}/products',
        operationId: 'campaigns.public.products',
        tags: ['Discount Campaigns'],
        summary: 'Get products of a specific campaign',
        description: 'Returns campaign details + its products with filters. Perfect for campaign landing pages.',
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
                                new OA\Property(property: 'icon', type: 'string', example: '⚡'),
                                new OA\Property(property: 'color', type: 'string', example: '#DC2626'),
                                new OA\Property(property: 'banner_image', type: 'string', nullable: true),
                                new OA\Property(property: 'ends_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'time_remaining_seconds', type: 'integer', example: 3600),
                            ]
                        ),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Campaign not found or inactive')
        ]
    )]
    public function products(Request $request, string $slug)
    {
        $campaign = DiscountCampaign::where('slug', $slug)
            ->active()
            ->firstOrFail();
        $result = $this->productService->listByCampaign($campaign, $request);
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
    #[OA\Get(
        path: '/api/admin/campaigns',
        operationId: 'campaigns.admin.index',
        tags: ['Discount Campaigns'],
        summary: 'List all campaigns (admin)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'is_active',
                in: 'query',
                schema: new OA\Schema(type: 'boolean')
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 20)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'All campaigns'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function adminIndex(Request $request)
    {
        $query = DiscountCampaign::withCount('discounts');

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $campaigns = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => DiscountCampaignResource::collection($campaigns),
            'meta' => [
                'current_page' => $campaigns->currentPage(),
                'last_page' => $campaigns->lastPage(),
                'per_page' => $campaigns->perPage(),
                'total' => $campaigns->total(),
            ],
        ]);
    }
    #[OA\Post(
        path: '/api/admin/campaigns',
        operationId: 'campaigns.admin.store',
        tags: ['Discount Campaigns'],
        summary: 'Create new campaign',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'slug'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Flash Sale'),
                    new OA\Property(property: 'slug', type: 'string', example: 'flash-sale'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'icon', type: 'string', nullable: true, example: '⚡'),
                    new OA\Property(property: 'color', type: 'string', nullable: true, example: '#DC2626'),
                    new OA\Property(property: 'banner_image', type: 'string', nullable: true),
                    new OA\Property(property: 'priority', type: 'integer', example: 10),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Campaign created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(StoreDiscountCampaignRequest $request)
    {
        $campaign = DiscountCampaign::create($request->validated());

        return (new DiscountCampaignResource($campaign))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    #[OA\Get(
        path: '/api/admin/campaigns/{campaign}',
        operationId: 'campaigns.admin.show',
        tags: ['Discount Campaigns'],
        summary: 'Show campaign details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'campaign',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Campaign details'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(DiscountCampaign $campaign)
    {
        $campaign->load(['discounts']);
        return new DiscountCampaignResource($campaign);
    }
    #[OA\Put(
        path: '/api/admin/campaigns/{campaign}',
        operationId: 'campaigns.admin.update',
        tags: ['Discount Campaigns'],
        summary: 'Update campaign',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'campaign',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string'),
                    new OA\Property(property: 'description', type: 'string', nullable: true),
                    new OA\Property(property: 'icon', type: 'string', nullable: true),
                    new OA\Property(property: 'color', type: 'string', nullable: true),
                    new OA\Property(property: 'priority', type: 'integer'),
                    new OA\Property(property: 'is_active', type: 'boolean'),
                    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
            new OA\Response(response: 404, description: 'Not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateDiscountCampaignRequest $request, DiscountCampaign $campaign)
    {
        $campaign->update($request->validated());
        return new DiscountCampaignResource($campaign);
    }
    #[OA\Delete(
        path: '/api/admin/campaigns/{campaign}',
        operationId: 'campaigns.admin.destroy',
        tags: ['Discount Campaigns'],
        summary: 'Delete campaign',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'campaign',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(DiscountCampaign $campaign)
    {
        $campaign->delete();

        return response()->json([
            'success' => true,
            'message' => 'Campaign deleted successfully',
        ]);
    }
    private function calculateTimeRemaining(DiscountCampaign $campaign): ?int
    {
        if (!$campaign->ends_at) {
            return null;
        }

        $remaining = now()->diffInSeconds($campaign->ends_at, false);

        return $remaining > 0 ? (int) $remaining : 0;
    }
}