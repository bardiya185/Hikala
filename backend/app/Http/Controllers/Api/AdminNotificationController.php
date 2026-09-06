<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Notification\AdminNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Admin Notifications", description: "Admin tools to send broadcasts and promotional notifications")]
class AdminNotificationController extends Controller
{
    public function __construct(
        private AdminNotificationService $notificationService
    ) {}

    // ================================================================
    // 📢 Send broadcast notification to all users
    // ================================================================
    #[OA\Post(
        path: '/api/admin/notifications/broadcast',
        tags: ['Admin Notifications'],
        summary: 'Send broadcast notification to all users',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['title', 'message'],
                properties: [
                    new OA\Property(property: 'title', type: 'string', example: 'Spring Sale Started!'),
                    new OA\Property(property: 'message', type: 'string', example: 'Up to 50% discount on all selected products.'),
                    new OA\Property(property: 'url', type: 'string', nullable: true, example: '/campaigns/spring-sale'),
                ]
            )
        ),
        responses: [new OA\Response(response: 200, description: 'Broadcast queued successfully')]
    )]
    public function sendBroadcast(Request $request): JsonResponse
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'url'     => 'nullable|string',
        ]);

        $this->notificationService->sendBroadcastNotification(
            title: $request->title,
            message: $request->message,
            url: $request->url
        );

        return response()->json([
            'success' => true,
            'message' => 'Broadcast notification queued for all users successfully.'
        ]);
    }

    // ================================================================
    // 💖 Send notification to users who wishlisted a product
    // ================================================================
    #[OA\Post(
        path: '/api/admin/notifications/wishlist-discount/{product}',
        tags: ['Admin Notifications'],
        summary: 'Notify users who wishlisted a specific product',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'product', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [new OA\Response(response: 200, description: 'Notification queued for target users')]
    )]
    public function notifyWishlist(Product $product): JsonResponse
    {
        $notifiedCount = $this->notificationService->notifyWishlistUsersProductDiscounted($product);

        if ($notifiedCount === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No users have this product in their wishlist.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Discount notification successfully queued for {$notifiedCount} interested user(s)."
        ]);
    }
}