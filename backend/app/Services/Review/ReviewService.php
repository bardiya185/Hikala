<?php

namespace App\Services\Review;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ReviewService
{
    public function getProductReviews(Product $product, Request $request): array
    {
        $query = $product->reviews()
            ->approved()
            ->with('user')
            ->withCount(['likes', 'dislikes']);

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'rating', 'likes_count'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min((int) $request->get('per_page', 10), 50);
        $reviews = $query->paginate($perPage);
        $user = $request->user();
        if ($user) {
            $this->loadUserReactions($reviews, $user);
        }

        $ratingSummary = $this->getRatingSummary($product);

        return [
            'reviews' => $reviews,
            'rating_summary' => $ratingSummary,
        ];
    }
    public function store(User $user, array $data): Review
    {
        $this->ensureUserHasNotReviewed($user, $data['product_id']);

        $isBuyer = $this->checkIsBuyer($user, $data['product_id']);

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'body' => $data['body'],
            'rating' => $data['rating'],
            'advantages' => $data['advantages'] ?? null,
            'disadvantages' => $data['disadvantages'] ?? null,
            'status' => 'pending',
            'is_buyer' => $isBuyer,
        ]);

        $review->load('user');

        return $review;
    }
    public function update(Review $review, User $user, array $data): Review
    {
        $this->ensureOwnership($review, $user);

        $wasApproved = $review->status === 'approved';

        $review->update([
            'body' => $data['body'] ?? $review->body,
            'rating' => $data['rating'] ?? $review->rating,
            'advantages' => $data['advantages'] ?? $review->advantages,
            'disadvantages' => $data['disadvantages'] ?? $review->disadvantages,
            'status' => 'pending',
        ]);

        if ($wasApproved) {
            $this->updateProductRating($review->product_id);
        }

        $review->load('user');

        return $review;
    }
    public function delete(Review $review, User $user): void
    {
        $this->ensureOwnership($review, $user);

        $productId = $review->product_id;
        $wasApproved = $review->status === 'approved';

        $review->delete();

        if ($wasApproved) {
            $this->updateProductRating($productId);
        }
    }
    public function getUserReview(User $user, Product $product): ?Review
    {
        return Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();
    }
    public function approve(Review $review): Review
    {
        $review->update(['status' => 'approved']);
        $this->updateProductRating($review->product_id);

        return $review->load('user');
    }
    public function reject(Review $review): Review
    {
        $review->update(['status' => 'rejected']);
        $this->updateProductRating($review->product_id);

        return $review->load('user');
    }
    public function getAdminReviews(Request $request): LengthAwarePaginator
    {
        $query = Review::with(['user', 'product'])
            ->withCount(['likes', 'dislikes'])
            ->latest();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $perPage = min((int) $request->get('per_page', 20), 100);

        return $query->paginate($perPage);
    }

    private function ensureUserHasNotReviewed(User $user, int $productId): void
    {
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            abort(422, 'You have already submitted a review for this product.');
        }
    }

    private function ensureOwnership(Review $review, User $user): void
    {
        if ($review->user_id !== $user->id) {
            abort(403, 'You are not allowed to perform this action.');
        }
    }

    private function checkIsBuyer(User $user, int $productId): bool
    {
        return OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            ->whereHas('variant', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();
    }

    private function updateProductRating(int $productId): void
    {
        $average = Review::where('product_id', $productId)
            ->approved()
            ->avg('rating');

        Product::where('id', $productId)->update([
            'rating' => $average ? round($average, 2) : 0,
        ]);
    }

    private function loadUserReactions($reviews, User $user): void
    {
        $reviewIds = $reviews->pluck('id');

        $userReactions = \App\Models\ReviewReaction::where('user_id', $user->id)
            ->whereIn('review_id', $reviewIds)
            ->get()
            ->keyBy('review_id');

        $reviews->getCollection()->transform(function ($review) use ($userReactions) {
            $review->setRelation('reactions', collect([
                $userReactions->get($review->id)
            ])->filter());
            return $review;
        });
    }

    private function getRatingSummary(Product $product): array
    {
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

        return [
            'average' => $averageRating,
            'total' => $totalReviews,
            'breakdown' => [
                5 => $ratingStats[5] ?? 0,
                4 => $ratingStats[4] ?? 0,
                3 => $ratingStats[3] ?? 0,
                2 => $ratingStats[2] ?? 0,
                1 => $ratingStats[1] ?? 0,
            ],
        ];
    }
}