<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Creating wishlists...');

        if (!Schema::hasTable('wishlists')) {
            $this->command->warn('⚠️ wishlists table not found. Run migrations first.');
            return;
        }

        DB::table('wishlists')->truncate();

        $users = DB::table('users')->pluck('id')->toArray();
        $products = DB::table('products')->pluck('id')->toArray();

        if (empty($users) || empty($products)) {
            $this->command->warn('⚠️ No users or products found.');
            return;
        }

        $count = 0;
        $usedPairs = [];

        // هر کاربر بین 2 تا 10 محصول در wishlist داشته باشه
        foreach ($users as $userId) {
            $numberOfItems = rand(2, min(10, count($products)));

            shuffle($products);
            $selectedProducts = array_slice($products, 0, $numberOfItems);

            foreach ($selectedProducts as $productId) {
                $key = "{$userId}-{$productId}";

                if (in_array($key, $usedPairs)) {
                    continue;
                }

                $usedPairs[] = $key;

                DB::table('wishlists')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 5)),
                ]);

                $count++;
            }
        }

        $this->command->info("✅ {$count} wishlist items created!");

        // Statistics
        $this->command->newLine();
        $this->command->info('📊 Statistics:');
        $this->command->line('   • Total items: ' . DB::table('wishlists')->count());
        $this->command->line('   • Users with wishlist: ' . DB::table('wishlists')->distinct('user_id')->count('user_id'));
        $this->command->line('   • Products in wishlists: ' . DB::table('wishlists')->distinct('product_id')->count('product_id'));

        // Top 5 most wishlisted products
        $this->command->newLine();
        $this->command->info('🏆 Top 5 Most Wishlisted Products:');

        $topProducts = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', '=', 'products.id')
            ->select('products.id', 'products.title', DB::raw('COUNT(*) as wishlist_count'))
            ->groupBy('products.id', 'products.title')
            ->orderByDesc('wishlist_count')
            ->limit(5)
            ->get();

        foreach ($topProducts as $product) {
            $this->command->line("   #{$product->id}: {$product->title} → ❤️ {$product->wishlist_count}");
        }
    }
}