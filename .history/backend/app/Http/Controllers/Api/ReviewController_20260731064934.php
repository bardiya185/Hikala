<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request, Product $product)
    {
        $query = $product->reviews()
            ->approved()
            ->with('user')
            ->latest();

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['created_at', 'rating'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min((int) $request->get('per_page', 10), 50);
        $reviews = $query->paginate($perPage);

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

    public function store(StoreReviewRequest $request)
    {
        $user = $request->user();
        $productId = $request->product_id;

        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a review for this product.',
            ], 422);
        }

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
            'message' => 'Your review has been submitted and is awaiting approval.',
            'data' => new ReviewResource($review),
        ], 201);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to update this review.',
            ], 403);
        }

        $review->update([
            'body' => $request->input('body', $review->body),
            'rating' => $request->input('rating', $review->rating),
            'advantages' => $request->input('advantages', $review->advantages),
            'disadvantages' => $request->input('disadvantages', $review->disadvantages),
            'status' => 'pending',
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Your review has been updated and is awaiting approval again.',
            'data' => new ReviewResource($review),
        ]);
    }

    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not allowed to delete this review.',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Your review has been deleted successfully.',
        ]);
    }

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

    public function adminIndex(Request $request)
    {
        $query = Review::with(['user', 'product'])->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

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

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);

        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);

        $this->updateProductRating($review->product_id);

        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully.',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

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