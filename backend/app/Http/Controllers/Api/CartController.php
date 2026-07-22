<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Cart",
    description: "Shopping cart management for users and guests"
)]
#[OA\Schema(
    schema: "Cart",
    title: "Cart",
    description: "Shopping cart model",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "items_count", type: "integer", example: 3),
        new OA\Property(
            property: "items",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "quantity", type: "integer", example: 2),
                    new OA\Property(property: "base_price", type: "number", example: 1000),
                    new OA\Property(property: "final_price", type: "number", example: 800),
                    new OA\Property(property: "discount_amount", type: "number", example: 200),
                    new OA\Property(property: "discount_percent", type: "integer", example: 20),
                    new OA\Property(property: "total", type: "number", example: 1600),
                ]
            )
        ),
        new OA\Property(
            property: "summary",
            type: "object",
            properties: [
                new OA\Property(property: "subtotal", type: "number", example: 2000),
                new OA\Property(property: "products_discount", type: "number", example: 400),
                new OA\Property(property: "total_before_coupon", type: "number", example: 1600),
                new OA\Property(property: "coupon_discount", type: "number", example: 100),
                new OA\Property(property: "final_total", type: "number", example: 1500),
            ]
        ),
        new OA\Property(property: "is_empty", type: "boolean", example: false),
    ]
)]
class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    // ================================================================
    // 🛒 GET CART
    // ================================================================
    #[OA\Get(
        path: '/api/cart',
        tags: ['Cart'],
        summary: 'Get current cart',
        description: 'Returns cart for logged-in user or guest (based on session). Both authenticated users and guests can use this endpoint.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                description: 'Session ID for guest users (optional if authenticated)',
                schema: new OA\Schema(type: 'string', example: 'guest-abc123')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Cart retrieved',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Cart'),
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $cart = $this->cartService->getOrCreate(
            $request->user(),
            $request->header('X-Session-Id')
        );

        $cart->load(['items.variant.product.images', 'coupon.discount']);

        return response()->json([
            'success' => true,
            'data' => new CartResource($cart),
        ]);
    }

    // ================================================================
    // ➕ ADD ITEM
    // ================================================================
    #[OA\Post(
        path: '/api/cart/items',
        tags: ['Cart'],
        summary: 'Add item to cart',
        description: 'Add a product variant to cart. Available for both users and guests.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_variant_id'],
                properties: [
                    new OA\Property(property: 'product_variant_id', type: 'integer', example: 5),
                    new OA\Property(property: 'quantity', type: 'integer', example: 2, default: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Item added'),
            new OA\Response(response: 422, description: 'Validation error or insufficient stock'),
        ]
    )]
    public function addItem(AddToCartRequest $request)
    {
        try {
            $cart = $this->cartService->getOrCreate(
                $request->user(),
                $request->header('X-Session-Id')
            );

            $variant = ProductVariant::findOrFail($request->product_variant_id);

            $this->cartService->addItem(
                $cart,
                $variant,
                $request->get('quantity', 1)
            );

            $cart->load(['items.variant.product.images', 'coupon.discount']);

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'data' => new CartResource($cart),
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ================================================================
    // ✏️ UPDATE QUANTITY
    // ================================================================
    #[OA\Put(
        path: '/api/cart/items/{item}',
        tags: ['Cart'],
        summary: 'Update item quantity',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'item',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['quantity'],
                properties: [
                    new OA\Property(property: 'quantity', type: 'integer', example: 3),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Quantity updated'),
            new OA\Response(response: 404, description: 'Item not found'),
            new OA\Response(response: 422, description: 'Insufficient stock'),
        ]
    )]
    public function updateItem(UpdateCartItemRequest $request, CartItem $item)
    {
        try {
            $this->authorizeCartAccess($request, $item);

            $this->cartService->updateQuantity($item, $request->quantity);

            $cart = $item->cart->fresh();
            $cart->load(['items.variant.product.images', 'coupon.discount']);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated',
                'data' => new CartResource($cart),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ================================================================
    // ❌ REMOVE ITEM
    // ================================================================
    #[OA\Delete(
        path: '/api/cart/items/{item}',
        tags: ['Cart'],
        summary: 'Remove item from cart',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'item',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Item removed'),
            new OA\Response(response: 404, description: 'Item not found'),
        ]
    )]
    public function removeItem(Request $request, CartItem $item)
    {
        try {
            $this->authorizeCartAccess($request, $item);

            $cart = $item->cart;
            $this->cartService->removeItem($item);

            $cart->load(['items.variant.product.images', 'coupon.discount']);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'data' => new CartResource($cart->fresh()),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        }
    }

    // ================================================================
    // 🗑️ CLEAR CART
    // ================================================================
    #[OA\Delete(
        path: '/api/cart',
        tags: ['Cart'],
        summary: 'Clear entire cart',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Cart cleared'),
        ]
    )]
    public function clear(Request $request)
    {
        $cart = $this->cartService->getOrCreate(
            $request->user(),
            $request->header('X-Session-Id')
        );

        $this->cartService->clear($cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'data' => new CartResource($cart->fresh()->load('items')),
        ]);
    }

    // ================================================================
    // 🎟️ APPLY COUPON
    // ================================================================
    #[OA\Post(
        path: '/api/cart/coupon',
        tags: ['Cart'],
        summary: 'Apply coupon to cart',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['code'],
                properties: [
                    new OA\Property(property: 'code', type: 'string', example: 'SUMMER20'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Coupon applied'),
            new OA\Response(response: 422, description: 'Invalid coupon'),
        ]
    )]
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        try {
            $cart = $this->cartService->getOrCreate(
                $request->user(),
                $request->header('X-Session-Id')
            );

            $this->cartService->applyCoupon($cart, $request->code);

            $cart->load(['items.variant.product.images', 'coupon.discount']);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'data' => new CartResource($cart),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ================================================================
    // ❌ REMOVE COUPON
    // ================================================================
    #[OA\Delete(
        path: '/api/cart/coupon',
        tags: ['Cart'],
        summary: 'Remove coupon from cart',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'X-Session-Id',
                in: 'header',
                required: false,
                schema: new OA\Schema(type: 'string')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Coupon removed'),
        ]
    )]
    public function removeCoupon(Request $request)
    {
        $cart = $this->cartService->getOrCreate(
            $request->user(),
            $request->header('X-Session-Id')
        );

        $this->cartService->removeCoupon($cart);

        $cart->load(['items.variant.product.images']);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed',
            'data' => new CartResource($cart),
        ]);
    }

    // ================================================================
    // 🔀 MERGE GUEST CART (after login)
    // ================================================================
    #[OA\Post(
        path: '/api/cart/merge',
        tags: ['Cart'],
        summary: 'Merge guest cart with user cart',
        description: 'Called after user login to merge their guest cart with their user cart. Requires authentication.',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['session_id'],
                properties: [
                    new OA\Property(property: 'session_id', type: 'string', example: 'guest-abc123'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Carts merged'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function mergeCart(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
        ]);

        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $cart = $this->cartService->mergeGuestCart(
            $request->user(),
            $request->session_id
        );

        $cart->load(['items.variant.product.images', 'coupon.discount']);

        return response()->json([
            'success' => true,
            'message' => 'Carts merged successfully',
            'data' => new CartResource($cart),
        ]);
    }

    // ================================================================
    // 🔒 Helper: Check Cart Access
    // ================================================================
    private function authorizeCartAccess(Request $request, CartItem $item): void
    {
        $cart = $item->cart;

        if ($request->user()) {
            if ($cart->user_id !== $request->user()->id) {
                throw new \Exception('You do not have access to this cart item');
            }
            return;
        }

        $sessionId = $request->header('X-Session-Id');
        if (!$sessionId || $cart->session_id !== $sessionId) {
            throw new \Exception('You do not have access to this cart item');
        }
    }
}