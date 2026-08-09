<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // ================================================================
    // نظرات تایید شده یک محصول (عمومی)
    // ================================================================
    public function index(Request $request, Product $product)
    {
        $query = $product->reviews()
            ->approved()
            ->with('user')
            ->latest();

        // فیلتر بر اساس امتیاز
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        // سورت
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['created_at', 'rating'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min((int) $request->get('per_page', 10), 50);
        $reviews = $query->paginate($perPage);

        // آمار امتیازها
        $ratingStats = $product->reviews()
            ->approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        $totalReviews = array_sum($ratingStats);
        $averageRating = $totalReviews > 0
            ? round(
                collect($ratingStats)->map(fn($count, $rating) => $rating * $count)->sum() / $totalReviews,
                1
              )
            : 0;

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'rating_summary' => [
                'average' => $averageRating,
                'total' => $totalReviews,
                'breakdown' => [
                    5 => $ratingStats[5] ?? 0,
                    4 => $ratingStats[4] ?? 0,
                    3 => $ratingStats[3] ?? 0,
                    2 => $ratingStats[2] ?? 0,
                    1 => $ratingStats[1] ?? 0,
                ],
            ],
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'has_more' => $reviews->hasMorePages(),
            ],
        ]);
    }

    // ================================================================
    // ثبت نظر (کاربر لاگین‌کرده)
    // ================================================================
    public function store(StoreReviewRequest $request)
    {
        $user = $request->user();
        $productId = $request->product_id;

        // چک کردن اینکه قبلاً نظر نداده باشه
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'شما قبلاً برای این محصول نظر ثبت کرده‌اید.',
            ], 422);
        }

        // چک کردن خریدار بودن
        $isBuyer = OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            ->whereHas('variant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'body' => $request->body,
            'rating' => $request->rating,
            'advantages' => $request->advantages,
            'disadvantages' => $request->disadvantages,
            'status' => 'pending',
            'is_buyer' => $isBuyer,
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'نظر شما ثبت شد و پس از تایید نمایش داده می‌شود.',
            'data' => new ReviewResource($review),
        ], 201);
    }

    // ================================================================
    // ویرایش نظر (فقط صاحب نظر)
    // ================================================================
    public function update(UpdateReviewRequest $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'شما اجازه ویرایش این نظر را ندارید.',
            ], 403);
        }

        $review->update([
            'body' => $request->input('body', $review->body),
            'rating' => $request->input('rating', $review->rating),
            'advantages' => $request->input('advantages', $review->advantages),
            'disadvantages' => $request->input('disadvantages', $review->disadvantages),
            'status' => 'pending', // بعد از ویرایش دوباره باید تایید بشه
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'نظر شما ویرایش شد و پس از تایید مجدد نمایش داده می‌شود.',
            'data' => new ReviewResource($review),
        ]);
    }

    // ================================================================
    // حذف نظر (فقط صاحب نظر)
    // ================================================================
    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'شما اجازه حذف این نظر را ندارید.',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'نظر شما حذف شد.',
        ]);
    }

    // ================================================================
    // نظر خود کاربر برای یک محصول
    // ================================================================
    public function myReview(Request $request, Product $product)
    {
        $user = $request->user();

        $review = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$review) {
            return response()->json([
                'success' => true,
                'data' => null,
                'can_review' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review),
            'can_review' => false,
        ]);
    }

    // ================================================================
    // لیست نظرات (ادمین)
    // ================================================================
    public function adminIndex(Request $request)
    {
        $query = Review::with(['user', 'product'])
            ->latest();

        // فیلتر وضعیت
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فیلتر محصول
        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $perPage = min((int) $request->get('per_page', 20), 100);
        $reviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'has_more' => $reviews->hasMorePages(),
            ],
        ]);
    }

    // ================================================================
    // تایید نظر (ادمین)
    // ================================================================
    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        // آپدیت rating محصول
        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'نظر تایید شد.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    // ================================================================
    // رد نظر (ادمین)
    // ================================================================
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        // آپدیت rating محصول
        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'نظر رد شد.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    // ================================================================
    // آپدیت rating محصول
    // ================================================================
    private function updateProductRating(int $productId): void
    {
        $average = Review::where('product_id', $productId)
            ->approved()
            ->avg('rating');

        Product::where('id', $productId)->update([
            'rating' => $average ? round($average, 1) : 0,
        ]);
    }
}