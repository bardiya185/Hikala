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
                'total' => $orders->total(),
            ],
        ]);
    }

    // ================================================================
    // 🔍 SHOW SINGLE ORDER
    // ================================================================
    #[OA\Get(
        path: '/api/orders/{order}',
        tags: ['Orders'],
        summary: 'Get order details',
        description: 'Get full details of a specific order (must belong to the user)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Order details'),
            new OA\Response(response: 403, description: 'Access denied'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Request $request, int $order)
    {
        try {
            $orderModel = $this->orderService->getUserOrder($request->user(), $order);

            return response()->json([
                'success' => true,
                'data' => new OrderResource($orderModel),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        }
    }

    // ================================================================
    // 🛒 PLACE ORDER (Checkout)
    // ================================================================
    #[OA\Post(
        path: '/api/orders',
        tags: ['Orders'],
        summary: 'Place new order from cart',
        description: 'Convert current cart into an order',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['address_id', 'payment_method'],
                properties: [
                    new OA\Property(property: 'address_id', type: 'integer', example: 1),
                    new OA\Property(
                        property: 'payment_method',
                        type: 'string',
                        enum: ['online', 'cash_on_delivery', 'wallet'],
                        example: 'online'
                    ),
                    new OA\Property(property: 'customer_note', type: 'string', nullable: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order placed successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Order'),
                        new OA\Property(
                            property: 'payment',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'payment_url', type: 'string', nullable: true),
                                new OA\Property(property: 'transaction_id', type: 'string', nullable: true),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 422, description: 'Validation or business error'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function store(PlaceOrderRequest $request)
    {
        try {
            $user = $request->user();
            
            // پیدا کردن آدرس
            $address = Address::findOrFail($request->address_id);
            
            // پیدا کردن سبد کاربر
            $cart = $this->cartService->getOrCreate($user, null);
            $cart->load(['items.variant.product.images', 'items.variant.attributeValues.attribute', 'coupon.discount']);
            
            // تبدیل payment_method به Enum
            $paymentMethod = PaymentMethod::from($request->payment_method);
            
            // ساخت سفارش
            $order = $this->creationService->createFromCart(
                $cart,
                $user,
                $address,
                $paymentMethod,
                $request->customer_note
            );
            
            // اگه پرداخت آنلاینه، لینک پرداخت رو بساز
            $paymentData = null;
            if ($paymentMethod === PaymentMethod::ONLINE) {
                $paymentData = $this->paymentService->initiate($order);
                
                // ذخیره transaction_id
                $order->update([
                    'transaction_id' => $paymentData['transaction_id'],
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => new OrderResource($order),
                'payment' => $paymentData,
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // ================================================================
    // ❌ CANCEL ORDER
    // ================================================================
    #[OA\Post(
        path: '/api/orders/{order}/cancel',
        tags: ['Orders'],
        summary: 'Cancel order',
        description: 'Cancel an order (only allowed before delivery)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'reason', type: 'string', example: 'Changed my mind'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Order canceled'),
            new OA\Response(response: 403, description: 'Cannot cancel'),
        ]
    )]
    public function cancel(Request $request, Order $order)
    {
        try {
            $order = $this->orderService->cancelOrder(
                $order,
                $request->user(),
                $request->input('reason')
            );

            $order->load(['items', 'address', 'statusHistory']);

            return response()->json([
                'success' => true,
                'message' => 'Order canceled successfully',
                'data' => new OrderResource($order),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        }
    }

    // ================================================================
    // 🔄 REQUEST REFUND
    // ================================================================
    #[OA\Post(
        path: '/api/orders/{order}/refund',
        tags: ['Orders'],
        summary: 'Request order refund',
        description: 'Request refund for a delivered order',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['reason'],
                properties: [
                    new OA\Property(property: 'reason', type: 'string', example: 'Product damaged'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Refund requested'),
            new OA\Response(response: 403, description: 'Cannot refund'),
        ]
    )]
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $order = $this->orderService->requestRefund(
                $order,
                $request->user(),
                $request->reason
            );

            $order->load(['items', 'address', 'statusHistory']);

            return response()->json([
                'success' => true,
                'message' => 'Refund requested successfully',
                'data' => new OrderResource($order),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_FORBIDDEN);
        }
    }

    // ================================================================
    // 💳 FAKE PAYMENT CALLBACK (For Testing)
    // ================================================================
    #[OA\Get(
        path: '/api/payment/fake/{order}/{transactionId}',
        tags: ['Orders'],
        summary: 'Fake payment callback (Testing only)',
        description: 'Simulates a successful payment. In production, replace with real gateway callback.',
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'transactionId',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Payment successful'),
            new OA\Response(response: 422, description: 'Payment failed'),
        ]
    )]
    public function fakePaymentCallback(Order $order, string $transactionId)
    {
        try {
            // Verify payment (fake - always successful)
            $result = $this->paymentService->verify($order, $transactionId);
            
            if (!$result['success']) {
                throw new \Exception('Payment verification failed');
            }
            
            // Update order status
            $statusService = app(\App\Services\Order\OrderStatusService::class);
            
            $order->update([
                'payment_status' => \App\Enums\PaymentStatus::PAID,
            ]);
            
            $order = $statusService->changeStatus(
                $order,
                \App\Enums\OrderStatus::PAID,
                null,
                "Payment received. Transaction: {$transactionId}"
            );
            
            $order->load(['items', 'address', 'statusHistory']);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'data' => new OrderResource($order),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}