<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Notifications", description: "User notifications management (Bell icon)")]
class NotificationController extends Controller
{
    #[OA\Get(
        path: '/api/notifications',
        tags: ['Notifications'],
        summary: 'Get all user notifications',
        security: [['bearerAuth' => []]],
        responses: [new OA\Response(response: 200, description: 'Notifications retrieved')]
    )]
    public function index(Request $request)
    {
        // گرفتن اعلانات با صفحه‌بندی (جدیدترین‌ها اول)
        $notifications = $request->user()->notifications()->paginate(15);

        return response()->json([
            'success' => true,
            // تعداد پیام‌های خوانده نشده برای نمایش روی آیکون زنگوله
            'unread_count' => $request->user()->unreadNotifications()->count(),
            'data' => NotificationResource::collection($notifications),
            'meta' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'total'        => $notifications->total(),
            ]
        ]);
    }

    #[OA\Put(
        path: '/api/notifications/{id}/read',
        tags: ['Notifications'],
        summary: 'Mark a specific notification as read',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))],
        responses: [new OA\Response(response: 200, description: 'Marked as read')]
    )]
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    #[OA\Put(
        path: '/api/notifications/read-all',
        tags: ['Notifications'],
        summary: 'Mark all notifications as read',
        security: [['bearerAuth' => []]],
        responses: [new OA\Response(response: 200, description: 'All marked as read')]
    )]
    public function markAllAsRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }

    #[OA\Delete(
        path: '/api/notifications/{id}',
        tags: ['Notifications'],
        summary: 'Delete a notification',
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string'))],
        responses: [new OA\Response(response: 200, description: 'Deleted')]
    )]
    public function destroy(Request $request, $id): JsonResponse
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->delete();
        }

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }
}