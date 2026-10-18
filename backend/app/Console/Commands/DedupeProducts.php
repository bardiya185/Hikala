<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DedupeProducts extends Command
{
    protected $signature = 'products:dedupe {--dry-run : Show what would be deleted without deleting}';

    protected $description = 'Remove duplicate products (same title + brand_id), keeping the lowest id. Cleans orphaned polymorphic discount rows; other children cascade via FK.';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        // Load lightweight identity columns; group in PHP to avoid
        // GROUP_CONCAT length limits and dialect quirks.
        $rows = DB::table('products')
            ->select('id', 'title', 'brand_id')
            ->orderBy('id')
            ->get();

        $groups = [];
        foreach ($rows as $r) {
            $key = ($r->title ?? '') . '|' . ($r->brand_id ?? 'null');
            $groups[$key][] = (int) $r->id;
        }

        $toDelete = [];
        $groupCount = 0;
        foreach ($groups as $ids) {
            if (count($ids) > 1) {
                $groupCount++;
                // ids are already ascending (orderBy id); keep the first.
                for ($i = 1, $n = count($ids); $i < $n; $i++) {
                    $toDelete[] = $ids[$i];
                }
            }
        }

        $this->info(sprintf(
            'Products: %d | unique (title+brand): %d | duplicate groups: %d | to delete: %d',
            $rows->count(),
            count($groups),
            $groupCount,
            count($toDelete)
        ));

        if (empty($toDelete)) {
            $this->info('Nothing to dedupe.');
            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->warn('Dry run — no rows deleted.');
            $this->line('Sample ids to delete: ' . implode(', ', array_slice($toDelete, 0, 25)));
            return self::SUCCESS;
        }

        $deleted = 0;
        DB::transaction(function () use ($toDelete, &$deleted) {
            foreach (array_chunk($toDelete, 300) as $chunk) {
                // Polymorphic discount rows have no FK -> clean manually.
                DB::table('discountables')
                    ->where('discountable_type', 'App\\Models\\Product')
                    ->whereIn('discountable_id', $chunk)
                    ->delete();

                // Everything else (images, variants, pivot, reviews, wishlist,
                // inventories) cascades from products / product_variants.
                $deleted += DB::table('products')->whereIn('id', $chunk)->delete();
            }
        });

        $this->info("Deleted {$deleted} duplicate products.");
        $this->info('Remaining products: ' . DB::table('products')->count());

        // Post-checks: ensure no surviving product lost all its images.
        $imageless = DB::table('products')
            ->whereNotIn('id', DB::table('product_images')->select('product_id'))
            ->count();
        $this->line($imageless === 0
            ? 'OK: every remaining product still has at least one image.'
            : "WARN: {$imageless} remaining products have no images (re-run ProductImageSeeder to relink).");

        return self::SUCCESS;
    }
}
