<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountCampaignRequest;
use App\Http\Requests\UpdateDiscountCampaignRequest;
use App\Http\Resources\DiscountCampaignResource;
use App\Models\DiscountCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Discount Campaigns",
    description: "Manage discount campaigns"
)]
class DiscountCampaignController extends Controller
{
    // ================================================================
    // 🌐 PUBLIC: List Active Campaigns
    // ================================================================
    #[OA\Get(
        path: '/api/campaigns',
        tags: ['Discount Campaigns'],
        summary: 'Get all active campaigns',
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

    // ================================================================
    // 🌐 PUBLIC: Show Campaign by Slug
    // ================================================================
    #[OA\Get(
        path: '/api/campaigns/{slug}',
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

    // ================================================================
    // 🔒 ADMIN: List All Campaigns (with pagination)
    // ================================================================
    #[OA\Get(
        path: '/api/admin/campaigns',
        tags: ['Discount Campaigns'],
        summary: 'List all campaigns (admin)',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'All campaigns'),
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

    // ================================================================
    // 🔒 ADMIN: Create Campaign
    // ================================================================
    #[OA\Post(
        path: '/api/admin/campaigns',
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
                    new OA\Property(property: 'priority', type: 'integer', example: 10),
                    new OA\Property(property: 'is_active', type: 'boolean', example: true),
                    new OA\Property(property: 'starts_at', type: 'string', format: 'date-time', nullable: true),
                    new OA\Property(property: 'ends_at', type: 'string', format: 'date-time', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Campaign created'),
        ]
    )]
    public function store(StoreDiscountCampaignRequest $request)
    {
        $campaign = DiscountCampaign::create($request->validated());

        return (new DiscountCampaignResource($campaign))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // ================================================================
    // 🔒 ADMIN: Show
    // ================================================================
    #[OA\Get(
        path: '/api/admin/campaigns/{campaign}',
        tags: ['Discount Campaigns'],
        summary: 'Show campaign details',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Campaign details'),
        ]
    )]
    public function show(DiscountCampaign $campaign)
    {
        $campaign->load(['discounts']);
        return new DiscountCampaignResource($campaign);
    }

    // ================================================================
    // 🔒 ADMIN: Update
    // ================================================================
    #[OA\Put(
        path: '/api/admin/campaigns/{campaign}',
        tags: ['Discount Campaigns'],
        summary: 'Update campaign',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Updated'),
        ]
    )]
    public function update(UpdateDiscountCampaignRequest $request, DiscountCampaign $campaign)
    {
        $campaign->update($request->validated());
        return new DiscountCampaignResource($campaign);
    }

    // ================================================================
    // 🔒 ADMIN: Delete
    // ================================================================
    #[OA\Delete(
        path: '/api/admin/campaigns/{campaign}',
        tags: ['Discount Campaigns'],
        summary: 'Delete campaign',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Deleted'),
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
}