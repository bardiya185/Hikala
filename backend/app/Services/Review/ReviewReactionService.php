<?php

namespace App\Services\Review;

use App\Models\Review;
use App\Models\ReviewReaction;
use App\Models\User;

class ReviewReactionService
{
    public function toggle(Review $review, User $user, string $type): array
    {
        $existing = ReviewReaction::where('user_id', $user->id)
            ->where('review_id', $review->id)
            ->first();
        if ($existing && $existing->type === $type) {
            $existing->delete();
            $this->updateCounts($review);

            return [
                'message' => 'Reaction removed.',
                'user_reaction' => null,
                'likes_count' => $review->likes()->count(),
                'dislikes_count' => $review->dislikes()->count(),
            ];
        }
        if ($existing) {
            $existing->update(['type' => $type]);
            $this->updateCounts($review);

            return [
                'message' => "Reaction changed to {$type}.",
                'user_reaction' => $type,
                'likes_count' => $review->likes()->count(),
                'dislikes_count' => $review->dislikes()->count(),
            ];
        }
        ReviewReaction::create([
            'user_id' => $user->id,
            'review_id' => $review->id,
            'type' => $type,
        ]);

        $this->updateCounts($review);

        return [
            'message' => 'Reaction added successfully.',
            'user_reaction' => $type,
            'likes_count' => $review->likes()->count(),
            'dislikes_count' => $review->dislikes()->count(),
        ];
    }

    private function updateCounts(Review $review): void
    {
        $review->update([
            'likes_count' => $review->likes()->count(),
            'dislikes_count' => $review->dislikes()->count(),
        ]);
    }
}