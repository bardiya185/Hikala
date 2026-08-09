<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Order\OrderCreationService;
use App\Services\Order\OrderService;
use App\Services\Payment\FakePaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Orders",
    description: "Order management for customers"
)]
#[OA\Schema(
    schema: "Order",
    title: "Order",
    description: "Order model",
    properties: [
        new OA\Property(property: "id", type: "integer", example: 1),
        new OA\Property(property: "order_number", type: "string", example: "ORD-20241027-0001"),
        new OA\Property(
            property: "status",
            type: "object",
            properties: [
                new OA\Property(property: "value", type: "string", example: "pending"),
                new OA\Property(property: "label", type: "string", example: "Pending Payment"),
                new OA\Property(property: "color", type: "string", example: "yellow"),
                new OA\Property(property: "icon", type: "string", example: "⏳"),
            ]
        ),
        new OA\Property(
            property: "payment_status",
            type: "object",
            properties: [
                new OA\Property(property: "value", type: "string", example: "pending"),
                new OA\Property(property: "label", type: "string", example: "Pending"),
            ]
        ),
        new OA\Property(
            property: "payment_method",
            type: "object",
            properties: [
                new OA\Property(property: "value", type: "string", example: "online"),
                new OA\Property(property: "label", type: "string", example: "Online Payment"),
            ]
        ),
        new OA\Property(property: "subtotal", type: "number", example: 1000000),
        new OA\Property(property: "discount_amount", type: "number", example: 100000),
        new OA\Property(property: "coupon_amount", type: "number", example: 50000),
        new OA\Property(property: "shipping_cost", type: "number", example: 50000),
        new OA\Property(property: "total_amount", type: "number", example: 900000),
        new OA\Property(property: "items_count", type: "integer", example: 3),
        new OA\Property(property: "can_be_canceled", type: "boolean", example: true),
        new OA\Property(property: "can_be_refunded", type: "boolean", example: false),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
    ]
)]
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderCreationService $creationService,
        private CartService $cartService,
        private FakePaymentService $paymentService,
    ) {}

    // ================================================================
    // 📋 LIST USER'S ORDERS
    // ================================================================
    #[OA\Get(
        path: '/api/orders',
        tags: ['Orders'],
        summary: 'List authenticated user orders',
        description: 'Get paginated list of orders for the logged-in user',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 10, maximum: 50)
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
                description: 'Orders list',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(ref: '#/components/schemas/Order')
                        ),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        $perPage = min((int) $request->get('per_page', 10), 50);
        $orders = $this->orderService->getUserOrders($request->user(), $perPage);

        return response()->json([
            'success' => true,
            'data' => OrderResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => 