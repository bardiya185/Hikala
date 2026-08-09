<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewReactionSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating review reactions (likes/dislikes)...');

        // ================================================================
        // 🧹 Clean previous data
        // ================================================================
        if (!Schema::hasTable('review_reactions')) {
            $this->command->warn('⚠️ review_reactions table does not exist. Please run migrations first.');
            return;
        }

        DB::table('review_reactions')->truncate();

        // ================================================================
        // 📥 Get required data
        // ================================================================
        $users = DB::table('users')->pluck('id')->toArray();

        // Only approved reviews get reactions
        $approvedReviews = DB::table('reviews')
            ->where('status', 'approved')
            ->pluck('id', 'user_id')
            ->toArray();

        $approvedReviewIds = DB::table('reviews')
            ->where('status', 'approved')
            ->pluck('id')
            ->toArray();

        if (empty($users)) {
            $this->command->warn('⚠️ No users found.');
            return;
        }

        if (empty($approvedReviewIds)) {
            $this->command->warn('⚠️ No approved reviews found. Please run ReviewSeeder first.');
            return;
        }

        // Get review owners to prevent self-reaction
        $reviewOwners = DB::table('reviews')
            ->where('status', 'approved')
            ->pluck('user_id', 'id')
            ->toArray();

        // ================================================================
        // 🔄 Create reactions
        // ================================================================
        $reactionCount = 0;
        $usedPairs = [];
        $types = ['like', 'like', 'like', 'dislike']; // 75% like, 25% dislike

        foreach ($approvedReviewIds as $reviewId) {
            // Each review gets 0-8 reactions
            $numberOfReactions = rand(0, min(8, count($users) - 1));

            if ($numberOfReactions === 0) {
                continue;
            }

            shuffle($users);

            $reactedCount = 0;

            foreach ($users as $userId) {
                if ($reactedCount >= $numberOfReactions) {
                    break;
                }

                // Don't let user react to their own review
                if (isset($reviewOwners[$reviewId]) && $reviewOwners[$reviewId] == $userId) {
                    continue;
                }

                // Unique check: user_id + review_id
                $pairKey = $userId . '-' . $reviewId;

                if (in_array($pairKey, $usedPairs)) {
                    continue;
                }

                $usedPairs[] = $pairKey;

                $type = $types[array_rand($types)];

                DB::table('review_reactions')->insert([
                    'user_id' => $userId,
                    'review_id' => $reviewId,
                    'type' => $type,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 5)),
                ]);

                $reactionCount++;
                $reactedCount++;
            }
        }

        $this->command->info("✅ {$reactionCount} reactions created!");

        // ================================================================
        // 🔄 Update likes_count and dislikes_count on reviews
        // ================================================================
        if (Schema::hasColumn('reviews', 'likes_count')) {
            $this->command->info('📊 Updating reaction counts on reviews...');

            // Update likes_count
            $likesCounts = DB::table('review_reactions')
                ->where('type', 'like')
                ->select('review_id')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('review_id')
                ->pluck('total', 'review_id')
                ->toArray();

            // Update dislikes_count
            $dislikesCounts = DB::table('review_reactions')
                ->where('type', 'dislike')
                ->select('review_id')
                ->selectRaw('COUNT(*) as total')
                ->groupBy('review_id')
                ->pluck('total', 'review_id')
                ->toArray();

            // Apply counts
            foreach ($approvedReviewIds as $reviewId) {
                DB::table('reviews')
                    ->where('id', $reviewId)
                    ->update([
                        'likes_count' => $likesCounts[$reviewId] ?? 0,
                        'dislikes_count' => $dislikesCounts[$reviewId] ?? 0,
                    ]);
            }

            $this->command->info('✅ Reaction counts updated!');
        }

        // ================================================================
        // 📊 Final report
        // ================================================================
        $this->command->newLine();
        $this->command->info('🎉 Review reaction seeding completed!');
        $this->command->info('📊 Statistics:');

        $totalReactions = DB::table('review_reactions')->count();
        $totalLikes = DB::table('review_reactions')->where('type', 'like')->count();
        $totalDislikes = DB::table('review_reactions')->where('type', 'dislike')->count();
        $reviewsWithReactions = DB::table('review_reactions')
            ->distinct('review_id')
            ->count('review_id');

        $this->command->line("   • Total Reactions: {$totalReactions}");
        $this->command->line("   • Likes: {$totalLikes}");
        $this->command->line("   • Dislikes: {$totalDislikes}");
        $this->command->line("   • Reviews with reactions: {$reviewsWithReactions}");

        // Top 5 most liked reviews
        $this->command->newLine();
        $this->command->info('🏆 Top 5 Most Liked Reviews:');

        $topLiked = DB::table('reviews')
            ->where('status', 'approved')
            ->where('likes_count', '>', 0)
            ->orderByDesc('likes_count')
            ->limit(5)
            ->get(['id', 'likes_count', 'dislikes_count', 'rating']);

        foreach ($topLiked as $review) {
            $this->command->line(
                "   Review #{$review->id}: 👍 {$review->likes_count} | 👎 {$review->dislikes_count} | ⭐ {$review->rating}"
            );
        }
    }
}