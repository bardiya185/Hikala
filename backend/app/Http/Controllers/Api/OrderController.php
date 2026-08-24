<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Address;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Order\OrderCreationService;
use App\Services\Order\OrderService;
use App\Services\Order\OrderStatusService;
use App\Services\Payment\FakePaymentService;
use App\Services\Delivery\DeliveryCalculator; 
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
                new OA\Property(property: "color", type: "string", example: "yellow"),
            ]
        ),
        new OA\Property(
            property: "payment_method",
            type: "object",
            properties: [
                new OA\Property(property: "value", type: "string", example: "online"),
                new OA\Property(property: "label", type: "string", example: "Online Payment"),
                new OA\Property(property: "icon", type: "string", example: "💳"),
            ]
        ),
        new OA\Property(property: "subtotal", type: "number", format: "float", example: 2000),
        new OA\Property(property: "discount_amount", type: "number", format: "float", example: 200),
        new OA\Property(property: "coupon_amount", type: "number", format: "float", example: 100),
        new OA\Property(property: "shipping_cost", type: "number", format: "float", example: 50000),
        new OA\Property(property: "total_amount", type: "number", format: "float", example: 51700),
        new OA\Property(property: "items_count", type: "integer", example: 3),
        new OA\Property(property: "customer_note", type: "string", nullable: true),
        new OA\Property(property: "can_be_canceled", type: "boolean", example: true),
        new OA\Property(property: "can_be_refunded", type: "boolean", example: false),
        new OA\Property(property: "paid_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "shipped_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "delivered_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "canceled_at", type: "string", format: "date-time", nullable: true),
        new OA\Property(property: "created_at", type: "string", format: "date-time"),
        new OA\Property(
            property: "items",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "product_title", type: "string", example: "iPhone 15"),
                    new OA\Property(property: "product_sku", type: "string", nullable: true),
                    new OA\Property(property: "product_image", type: "string", nullable: true),
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
            property: "status_history",
            type: "array",
            items: new OA\Items(
                properties: [
                    new OA\Property(property: "id", type: "integer"),
                    new OA\Property(property: "to_status", type: "object"),
                    new OA\Property(property: "note", type: "string", nullable: true),
                    new OA\Property(property: "created_at", type: "string", format: "date-time"),
                ]
            )
        ),
    ]
)]
class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private OrderCreationService $creationService,
        private OrderStatusService $statusService,
        private CartService $cartService,
        private FakePaymentService $paymentService,
        private DeliveryCalculator $deliveryCalculator
    ) {}
    #[OA\Post(
        path: '/api/orders/checkout',
        tags: ['Orders'],
        summary: 'Place an order from cart',
        description: 'Converts current cart to an order. Requires authentication and a valid address.',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['address_id', 'payment_method'],
                properties: [
                    new OA\Property(
                        property: 'address_id',
                        type: 'integer',
                        example: 1,
                        description: 'ID of the shipping address'
                    ),
                    new OA\Property(
                        property: 'payment_method',
                        type: 'string',
                        enum: ['online', 'cash_on_delivery', 'wallet'],
                        example: 'online'
                    ),
                    new OA\Property(
                        property: 'customer_note',
                        type: 'string',
                        nullable: true,
                        example: 'Please deliver in the morning',
                        maxLength: 1000
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'order', ref: '#/components/schemas/Order'),
                                new OA\Property(
                                    property: 'payment',
                                    type: 'object',
                                    properties: [
                                        new OA\Property(property: 'payment_url', type: 'string', nullable: true),
                                        new OA\Property(property: 'transaction_id', type: 'string', nullable: true),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validation error or empty cart'),
        ]
    )]
    public function checkout(PlaceOrderRequest $request)
    {
        try {
            $user = $request->user();
            
            $cart = $this->cartService->getOrCreate($user, null);
            $cart->load([
                'items.variant.product.images', 
                'items.variant.attributeValues.attribute', 
                'coupon.discount'
            ]);
            
            $address = Address::findOrFail($request->address_id);
            $paymentMethod = PaymentMethod::from($request->payment_method);
            $shippingMethod = $request->shipping_method 
                ? \App\Enums\ShippingMethod::from($request->shipping_method)
                : \App\Enums\ShippingMethod::STANDARD;

                $preferredTimeSlot = $request->preferred_delivery_time_slot
                ? \App\Enums\DeliveryTimeSlot::from($request->preferred_delivery_time_slot)
                : null;
            $order = $this->creationService->createFromCart(
                $cart,
                $user,
                $address,
                $paymentMethod,
                $request->customer_note,
                $shippingMethod,
                $request->preferred_delivery_date,  
                $preferredTimeSlot 
            );
            
            $paymentData = null;
            if ($paymentMethod === PaymentMethod::ONLINE) {
                $paymentData = $this->paymentService->initiate($order);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order' => new OrderResource($order),
                    'payment' => $paymentData,
                ],
            ], Response::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    #[OA\Get(
        path: '/api/orders',
        tags: ['Orders'],
        summary: 'List user orders',
        description: 'Get paginated list of authenticated user orders',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'status',
                in: 'query',
                description: 'Filter by status',
                schema: new OA\Schema(
                    type: 'string',
                    enum: ['pending', 'paid', 'processing', 'shipped', 'delivered', 'canceled', 'refunded']
                )
            ),
            new OA\Parameter(
                name: 'per_page',
                in: 'query',
                schema: new OA\Schema(type: 'integer', default: 10)
            ),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Orders list'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = min((int) $request->get('per_page', 10), 50);
        
        $query = Order::forUser($user->id)
            ->with(['items', 'address']);
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->latest()->paginate($perPage);
        
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
    #[OA\Get(
        path: '/api/orders/{order}',
        tags: ['Orders'],
        summary: 'Get order details',
        description: 'Get full details of a specific order including items and status history',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Order details'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function show(Request $request, Order $order)
    {
        try {
            $user = $request->user();
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this order',
                ], Response::HTTP_FORBIDDEN);
            }
            
            $order->load([
                'items',
                'address.province',
                'address.city',
                'coupon',
                'statusHistory.changedBy',
            ]);
            
            return response()->json([
                'success' => true,
                'data' => new OrderResource($order),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    #[OA\Post(
        path: '/api/orders/{order}/cancel',
        tags: ['Orders'],
        summary: 'Cancel an order',
        description: 'Cancel an order (only if status allows it: pending, paid, processing, shipped)',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: false,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'reason',
                        type: 'string',
                        nullable: true,
                        example: 'Changed my mind'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Order canceled'),
            new OA\Response(response: 403, description: 'Cannot cancel this order'),
            new OA\Response(response: 422, description: 'Order cannot be canceled at this stage'),
        ]
    )]
    public function cancel(Request $request, Order $order)
    {
        try {
            $order = $this->orderService->cancelOrder(
                $order,
                $request->user(),
                $request->get('reason')
            );
            
            $order->load(['items', 'statusHistory']);
            
            return response()->json([
                'success' => true,
                'message' => 'Order canceled successfully',
                'data' => new OrderResource($order),
            ]);
        if ($request->reason) {
            $order->update(['cancel_reason' => $request->reason]);
        }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    #[OA\Get(
        path: '/api/orders/delivery/options',
        tags: ['Orders'],
        summary: 'Get available delivery dates based on cart items',
        description: 'Returns available delivery dates calculated from cart items shipping features',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Delivery options'),
        ]
    )]
    public function deliveryOptions(Request $request)
    {
        $user = $request->user();
        $cart = $this->cartService->getOrCreate(
            $user,
            $request->header('X-Session-Id')
        );
        $cart->load(['items.variant.shippingFeatures']);
        if ($cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 422);
        }
        $dates = $this->deliveryCalculator->getAvailableDates($cart, 7);
        $info = $this->deliveryCalculator->getDeliveryInfo($cart);
        
        return response()->json([
            'success' => true,
            'data' => [
                'delivery_info' => $info,
                'dates' => $dates,
                'time_slots' => \App\Enums\DeliveryTimeSlot::options(),
            ],
        ]);
    }
    #[OA\Post(
        path: '/api/orders/{order}/refund',
        tags: ['Orders'],
        summary: 'Request refund',
        description: 'Request a refund for a delivered order',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['reason'],
                properties: [
                    new OA\Property(
                        property: 'reason',
                        type: 'string',
                        example: 'Product is defective',
                        minLength: 10
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Refund requested'),
            new OA\Response(response: 422, description: 'Cannot refund this order'),
        ]
    )]
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);
        
        try {
            $order = $this->orderService->requestRefund(
                $order,
                $request->user(),
                $request->reason
            );
            
            $order->load(['items', 'statusHistory']);
            
            return response()->json([
                'success' => true,
                'message' => 'Refund requested successfully',
                'data' => new OrderResource($order),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
    #[OA\Post(
        path: '/api/orders/{order}/pay',
        tags: ['Orders'],
        summary: 'Pay for order (Fake payment)',
        description: 'Simulates payment for testing. In production, this will be replaced with real gateway.',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(response: 200, description: 'Payment successful'),
            new OA\Response(response: 422, description: 'Order already paid or invalid'),
        ]
    )]
    public function pay(Request $request, Order $order)
    {
        try {
            $user = $request->user();
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this order',
                ], Response::HTTP_FORBIDDEN);
            }
            if ($order->payment_status === PaymentStatus::PAID) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order already paid',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $result = $this->paymentService->verify(
                $order,
                'TXN-FAKE-' . strtoupper(uniqid())
            );
            $order->update([
                'payment_status' => PaymentStatus::PAID,
                'transaction_id' => $result['transaction_id'],
                'paid_at' => now(),
            ]);
            $this->statusService->changeStatus(
                $order,
                OrderStatus::PAID,
                $user,
                "Payment received. TXN: {$result['transaction_id']}"
            );
            
            $order->refresh()->load(['items', 'statusHistory']);
            
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