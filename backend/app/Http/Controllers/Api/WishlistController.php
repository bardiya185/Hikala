<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Models\Product;
use App\Services\Wishlist\WishlistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Wishlist", description: "User wishlist management")]
class WishlistController extends Controller
{
    public function __construct(
        private WishlistService $wishlistService
    ) {}

    // ================================================================
    // Get User's Wishlist
    // ================================================================
    #[OA\Get(
        path: '/api/wishlist',
        tags: ['Wishlist'],
        summary: 'Get user wishlist',
        description: 'Returns paginated list of products in user wishlist.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', default: 20, maximum: 100)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishlist retrieved successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'count', type: 'integer', example: 5),
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
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $wishlist = $this->wishlistService->getUserWishlist($user, $request);
        $count = $this->wishlistService->getCount($user);

        return response()->json([
            'success' => true,
            'count' => $count,
            'data' => WishlistResource::collection($wishlist),
            'meta' => [
                'current_page' => $wishlist->currentPage(),
                'last_page' => $wishlist->lastPage(),
                'per_page' => $wishlist->perPage(),
                'total' => $wishlist->total(),
                'has_more' => $wishlist->hasMorePages(),
            ],
        ]);
    }

    // ================================================================
    // Add Product to Wishlist
    // ================================================================
    #[OA\Post(
        path: '/api/wishlist/{product}',
        tags: ['Wishlist'],
        summary: 'Add product to wishlist',
        description: 'Adds a product to user wishlist. If already exists, no error is thrown.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product added to wishlist',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product added to wishlist.'),
                        new OA\Property(property: 'in_wishlist', type: 'boolean', example: true),
                        new OA\Property(property: 'count', type: 'integer', example: 6),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function store(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        $this->wishlistService->add($user, $product);

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist.',
            'in_wishlist' => true,
            'count' => $this->wishlistService->getCount($user),
        ]);
    }

    // ================================================================
    // Remove Product from Wishlist
    // ================================================================
    #[OA\Delete(
        path: '/api/wishlist/{product}',
        tags: ['Wishlist'],
        summary: 'Remove product from wishlist',
        description: 'Removes a product from user wishlist.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product removed from wishlist',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Product removed from wishlist.'),
                        new OA\Property(property: 'in_wishlist', type: 'boolean', example: false),
                        new OA\Property(property: 'count', type: 'integer', example: 5),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        $this->wishlistService->remove($user, $product);

        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist.',
            'in_wishlist' => false,
            'count' => $this->wishlistService->getCount($user),
        ]);
    }

    // ================================================================
    // Toggle Product in Wishlist
    // ================================================================
    #[OA\Post(
        path: '/api/wishlist/{product}/toggle',
        tags: ['Wishlist'],
        summary: 'Toggle product in wishlist',
        description: 'If product is in wishlist, removes it. If not, adds it. Best for heart icon toggle.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishlist toggled successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'action', type: 'string', enum: ['added', 'removed'], example: 'added'),
                        new OA\Property(property: 'in_wishlist', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'count', type: 'integer', example: 6),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Product not found'),
        ]
    )]
    public function toggle(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        $result = $this->wishlistService->toggle($user, $product);

        return response()->json([
            'success' => true,
            'action' => $result['action'],
            'in_wishlist' => $result['in_wishlist'],
            'message' => $result['message'],
            'count' => $this->wishlistService->getCount($user),
        ]);
    }

    // ================================================================
    // Check if Product is in Wishlist
    // ================================================================
    #[OA\Get(
        path: '/api/wishlist/{product}/check',
        tags: ['Wishlist'],
        summary: 'Check if product is in wishlist',
        description: 'Returns whether a specific product is in user wishlist.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Check completed',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'in_wishlist', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function check(Request $request, Product $product): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'in_wishlist' => $this->wishlistService->isInWishlist($user, $product),
        ]);
    }

    // ================================================================
    // Clear Wishlist
    // ================================================================
    #[OA\Delete(
        path: '/api/wishlist',
        tags: ['Wishlist'],
        summary: 'Clear all wishlist items',
        description: 'Removes all products from user wishlist.',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishlist cleared',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Wishlist cleared successfully.'),
                        new OA\Property(property: 'deleted_count', type: 'integer', example: 5),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function clear(Request $request): JsonResponse
    {
        $user = $request->user();

        $deletedCount = $this->wishlistService->clear($user);

        return response()->json([
            'success' => true,
            'message' => 'Wishlist cleared successfully.',
            'deleted_count' => $deletedCount,
        ]);
    }
}