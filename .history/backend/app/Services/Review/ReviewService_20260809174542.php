<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ReviewService
{
    // ================================================================
    // Get Approved Reviews for a Product
    // ================================================================
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

        // Load user reactions if authenticated
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

    // ================================================================
    // Store Review
    // ================================================================
    public function store(User $user, array $data): array
    {
        $productId = $data['product_id'];

        // Check duplicate
        $exists = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            return [
                'success' => false,
                'message' => 'You have already submitted a review for this product.',
                'code' => 422,
            ];
        }

        // Detect buyer
        $isBuyer = $this->isBuyer($user, $productId);

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'body' => $data['body'],
            'rating' => $data['rating'],
            'advantages' => $data['advantages'] ?? null,
            'disadvantages' => $data['disadvantages'] ?? null,
            'status' => 'pending',
            'is_buyer' => $isBuyer,
        ]);

        $review->load('user');
        $review->loadCount(['likes', 'dislikes']);

        return [
            'success' => true,
            'message' => 'Your review has been submitted and is awaiting approval.',
            'review' => $review,
            'code' => 201,
        ];
    }

    // ================================================================
    // Update Review
    // ================================================================
    public function update(User $user, Review $review, array $data): array
    {
        if ($review->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'You are not allowed to update this review.',
                'code' => 403,
            ];
        }

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
        $review->loadCount(['likes', 'dislikes']);

        return [
            'success' => true,
            'message' => 'Your review has been updated and is awaiting approval again.',
            'review' => $review,
            'code' => 200,
        ];
    }

    // ================================================================
    // Delete Review
    // ================================================================
    public function delete(User $user, Review $review): array
    {
        if ($review->user_id !== $user->id) {
            return [
                'success' => false,
                'message' => 'You are not allowed to delete this review.',
                'code' => 403,
            ];
        }

        $productId = $review->product_id;
        $wasApproved = $review->status === 'approved';

        $review->delete();

        if ($wasApproved) {
            $this->updateProductRating($productId);
        }

        return [
            'success' => true,
            'message' => 'Your review has been deleted successfully.',
            'code' => 200,
        ];
    }

    // ================================================================
    // My Review
    // ================================================================
    public function myReview(User $user, Product $product): array
    {
        $review = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        return [
            'review' => $review,
            'can_review' => is_null($review),
        ];
    }

    // ================================================================
    // React (Like / Dislike)
    // ================================================================
    public function react(User $user, Review $review, string $type): array
    {
        $existing = ReviewReaction::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();

        // Toggle: same reaction → remove
        if ($existing && $existing->type === $type) {
            $existing->delete();
            $this->updateReactionCounts($review);

            return [
                'message' => 'Reaction removed.',
                'user_reaction' => null,
                'likes_count' => $review->likes_count,
                'dislikes_count' => $review->dislikes_count,
            ];
        }

        // Change: different reaction → update
        if ($existing) {
            $existing->update(['type' => $type]);
            $this->updateReactionCounts($review);

            return [
                'message' => "Reaction changed to {$type}.",
                'user_reaction' => $type,
                'likes_count' => $review->likes_count,
                'dislikes_count' => $review->dislikes_count,
            ];
        }

        // New reaction
        ReviewReaction::create([
            'user_id' => $user->id,
            'review_id' => $review->id,
            'type' => $type,
        ]);

        $this->updateReactionCounts($review);

        return [
            'message' => 'Reaction added successfully.',
            'user_reaction' => $type,
            'likes_count' => $review->likes_count,
            'dislikes_count' => $review->dislikes_count,
        ];
    }

    // ================================================================
    // Admin: List All Reviews
    // ================================================================
    public function adminIndex(Request $request): LengthAwarePaginator
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

    // ================================================================
    // Admin: Approve
    // ================================================================
    public function approve(Review $review): Review
    {
        $review->update(['status' => 'approved']);
        $this->updateProductRating($review->product_id);
        $review->load('user');
        $review->loadCount(['likes', 'dislikes']);

        return $review;
    }

    // ================================================================
    // Admin: Reject
    // ================================================================
    public function reject(Review $review): Review
    {
        $review->update(['status' => 'rejected']);
        $this->updateProductRating($review->product_id);
        $review->load('user');
        $review->loadCount(['likes', 'dislikes']);

        return $review;
    }

    // ================================================================
    // Private Helpers
    // ================================================================

    private function isBuyer(User $user, int $productId): bool
    {
        return OrderItem::whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('status', 'delivered');
            })
            