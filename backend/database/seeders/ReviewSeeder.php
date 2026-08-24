<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating reviews...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        if (Schema::hasTable('review_reactions')) {
            DB::table('review_reactions')->truncate();
        }

        DB::table('reviews')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $users = DB::table('users')->pluck('id')->toArray();
        $products = DB::table('products')->pluck('id')->toArray();

        if (empty($users)) {
            $this->command->warn('⚠️ No users found. Please seed users first.');
            return;
        }

        if (empty($products)) {
            $this->command->warn('⚠️ No products found. Please seed products first.');
            return;
        }
        $deliveredOrders = [];

        if (Schema::hasTable('orders')) {
            $deliveredOrders = DB::table('orders')
                ->where('status', 'delivered')
                ->pluck('id', 'user_id')
                ->toArray();
        }
        $reviewBodies = [
            'Great product! Exactly what I was looking for. The quality is excellent and it arrived on time.',
            'Very good quality for the price. I would definitely recommend this to anyone looking for a reliable product.',
            'The product works as described. Packaging was good and delivery was fast. Happy with my purchase.',
            'Amazing build quality and great performance. This is one of the best purchases I have made.',
            'Decent product overall. It does what it says but nothing extraordinary. Good value for money though.',
            'Absolutely love this product! The design is beautiful and the functionality is top notch.',
            'Not bad, but I expected a bit more for the price. The quality is okay but could be better.',
            'Excellent product with premium feel. Very satisfied with the purchase. Will buy again.',
            'Good product but the delivery took longer than expected. The product itself is fine and works well.',
            'Perfect! This is exactly what I needed. Great quality, fast shipping, and excellent customer service.',
            'The product exceeded my expectations. Very well made and durable. Highly recommended.',
            'Solid product with no issues so far. Been using it for a week and everything works perfectly.',
            'Fair product for the price point. It gets the job done but there is room for improvement.',
            'Outstanding quality! You can tell this is a premium product. Very happy with my purchase.',
            'Good value for money. The product is well built and performs as expected. No complaints.',
        ];

        $advantagesList = [
            ['High quality materials', 'Durable build'],
            ['Fast delivery', 'Good packaging'],
            ['Beautiful design', 'Easy to use'],
            ['Great performance', 'Value for money'],
            ['Lightweight', 'Compact size'],
            ['Energy efficient', 'Quiet operation'],
            ['Premium feel', 'Long lasting'],
            ['User friendly', 'Modern design'],
            ['Reliable performance', 'Good warranty'],
            ['Excellent build quality', 'Fast setup'],
        ];

        $disadvantagesList = [
            ['Price is a bit high'],
            ['Could be lighter'],
            ['Manual could be better'],
            ['Limited color options'],
            ['Takes time to set up'],
            [],
            [],
            [],
            ['Packaging could improve'],
            [],
        ];

        $statuses = ['pending', 'approved', 'approved', 'approved', 'rejected'];
        $hasLikesColumn = Schema::hasColumn('reviews', 'likes_count');
        $reviewCount = 0;
        $usedPairs = [];

        foreach ($products as $productId) {
            $numberOfReviews = rand(1, min(5, count($users)));

            shuffle($users);
            $selectedUsers = array_slice($users, 0, $numberOfReviews);

            foreach ($selectedUsers as $userId) {
                $pairKey = $userId . '-' . $productId;

                if (in_array($pairKey, $usedPairs)) {
                    continue;
                }

                $usedPairs[] = $pairKey;
                $isBuyer = false;

                if (isset($deliveredOrders[$userId]) && Schema::hasTable('order_items')) {
                    $isBuyer = DB::table('order_items')
                        ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
                        ->where('order_items.order_id', $deliveredOrders[$userId])
                        ->where('product_variants.product_id', $productId)
                        ->exists();
                }

                $rating = $this->weightedRating();
                $status = $statuses[array_rand($statuses)];

                $reviewData = [
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'body' => $reviewBodies[array_rand($reviewBodies)],
                    'rating' => $rating,
                    'advantages' => json_encode($advantagesList[array_rand($advantagesList)]),
                    'disadvantages' => json_encode($disadvantagesList[array_rand($disadvantagesList)]),
                    'status' => $status,
                    'is_buyer' => $isBuyer,
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(0, 10)),
                ];
                if ($hasLikesColumn) {
                    $reviewData['likes_count'] = 0;
                    $reviewData['dislikes_count'] = 0;
                }

                DB::table('reviews')->insert($reviewData);
                $reviewCount++;
            }
        }

        $this->command->info("✅ {$reviewCount} reviews created!");
        if (Schema::hasColumn('products', 'rating')) {
            $this->command->info('📊 Updating product ratings...');

            $productsWithReviews = DB::table('reviews')
                ->where('status', 'approved')
                ->select('product_id')
                ->selectRaw('ROUND(AVG(rating), 2) as avg_rating')
                ->groupBy('product_id')
                ->get();

            foreach ($productsWithReviews as $item) {
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->update(['rating' => $item->avg_rating]);
            }
            $productIdsWithReviews = $productsWithReviews->pluck('product_id')->toArray();
            $productsWithoutReviews = array_diff($products, $productIdsWithReviews);

            if (!empty($productsWithoutReviews)) {
                DB::table('products')
                    ->whereIn('id', $productsWithoutReviews)
                    ->update(['rating' => 0]);
            }

            $this->command->info('✅ Product ratings updated!');
        }
        $this->command->newLine();
        $this->command->info('🎉 Review seeding completed!');
        $this->command->info('📊 Statistics:');

        $total = DB::table('reviews')->count();
        $approved = DB::table('reviews')->where('status', 'approved')->count();
        $pending = DB::table('reviews')->where('status', 'pending')->count();
        $rejected = DB::table('reviews')->where('status', 'rejected')->count();
        $buyers = DB::table('reviews')->where('is_buyer', true)->count();

        $this->command->line("   • Total Reviews: {$total}");
        $this->command->line("   • Approved: {$approved}");
        $this->command->line("   • Pending: {$pending}");
        $this->command->line("   • Rejected: {$rejected}");
        $this->command->line("   • By Buyers: {$buyers}");

        $this->command->newLine();
        $this->command->info('📊 Rating Distribution:');

        for ($i = 5; $i >= 1; $i--) {
            $count = DB::table('reviews')
                ->where('status', 'approved')
                ->where('rating', $i)
                ->count();

            $bar = str_repeat('█', $count);
            $this->command->line("   ⭐ {$i}: {$bar} ({$count})");
        }
    }

    private function weightedRating(): int
    {
        $weights = [
            5 => 35,
            4 => 30,
            3 => 20,
            2 => 10,
            1 => 5,
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($weights as $rating => $weight) {
            $cumulative += $weight;

            if ($rand <= $cumulative) {
                return $rating;
            }
        }

        return 4;
    }
}