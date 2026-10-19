<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillCampaignDiscounts extends Command
{
    protected $signature = 'campaigns:fill-discounts {--per=12 : Discounted products per campaign} {--min=6 : Only top up when a campaign has fewer than this}';

    protected $description = 'Remove orphaned product-discount rows and ensure every active campaign has enough discounted live products';

    public function handle(): int
    {
        $per = max(1, (int) $this->option('per'));
        $min = max(1, (int) $this->option('min'));

        $liveProducts = DB::table('products')
            ->where('is_active', 1)
            ->pluck('id')
            ->all();

        if (empty($liveProducts)) {
            $this->warn('No active products found.');
            return self::SUCCESS;
        }

        // Drop polymorphic discount rows whose product no longer exists.
        $orphans = DB::table('discountables')
            ->where('discountable_type', 'App\\Models\\Product')
            ->whereNotIn('discountable_id', $liveProducts)
            ->delete();
        $this->info("Removed {$orphans} orphaned product-discount rows.");

        $campaigns = DB::table('discount_campaigns')
            ->where('is_active', true)
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($campaigns as $campaign) {
            $discount = DB::table('discounts')
                ->where('campaign_id', $campaign->id)
                ->where('is_active', true)
                ->orderByRaw("CASE WHEN type = 'percent' THEN 0 ELSE 1 END")
                ->orderBy('id')
                ->first();

            if (!$discount) {
                $discountId = DB::table('discounts')->insertGetId([
                    'campaign_id' => $campaign->id,
                    'name' => $campaign->name . ' Discount',
                    'type' => 'percent',
                    'value' => rand(15, 50),
                    'stackable' => false,
                    'used_quantity' => 0,
                    'priority' => $campaign->priority,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $discount = (object) ['id' => $discountId];
                $this->line("Created a discount for campaign '{$campaign->slug}'.");
            }

            $attached = DB::table('discountables')
                ->where('discount_id', $discount->id)
                ->where('discountable_type', 'App\\Models\\Product')
                ->pluck('discountable_id')
                ->all();

            if (count($attached) >= $min) {
                $this->line("Campaign '{$campaign->slug}' already has " . count($attached) . ' products.');
                continue;
            }

            // Spread products across campaigns so one product is not
            // discounted by two campaigns at once.
            $taken = DB::table('discountables')
                ->where('discountable_type', 'App\\Models\\Product')
                ->whereIn('discount_id', function ($query) {
                    $query->select('id')
                        ->from('discounts')
                        ->whereNotNull('campaign_id');
                })
                ->pluck('discountable_id')
                ->all();

            $candidates = array_values(array_diff($liveProducts, $taken, $attached));
            shuffle($candidates);

            $need = $per - count($attached);
            $picked = array_slice($candidates, 0, $need);

            $rows = array_map(fn ($productId) => [
                'discount_id' => $discount->id,
                'discountable_type' => 'App\\Models\\Product',
                'discountable_id' => $productId,
                'created_at' => now(),
                'updated_at' => now(),
            ], $picked);

            if ($rows) {
                DB::table('discountables')->insert($rows);
            }

            $this->info("Campaign '{$campaign->slug}': +" . count($picked) . ' products (now ' . (count($attached) + count($picked)) . ').');
        }

        return self::SUCCESS;
    }
}
