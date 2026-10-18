<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $dump = database_path('data/product_images.sql');
        if (is_file($dump)) {
            DB::unprepared(file_get_contents($dump));
            $this->command->info('Imported product_images dump.');
        }

        $root = storage_path('app/public/products');

        if (!File::isDirectory($root)) {
            $this->command->warn('No product image files found, skipping.');
            return;
        }

        $productIds = DB::table('products')->pluck('id')->all();
        $existing = DB::table('product_images')->pluck('product_id')->unique()->all();
        $missing = array_diff($productIds, $existing);

        $rows = [];
        foreach ($missing as $productId) {
            $dir = $root . DIRECTORY_SEPARATOR . $productId;
            if (!File::isDirectory($dir)) {
                continue;
            }

            $files = collect(File::files($dir))
                ->filter(fn ($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f->getFilename()))
                ->values();

            foreach ($files as $i => $file) {
                $rows[] = [
                    'product_id' => $productId,
                    'image_path' => 'products/' . $productId . '/' . $file->getFilename(),
                    'alt' => null,
                    'sort_order' => $i + 1,
                    'is_main' => $i === 0 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('product_images')->insert($chunk);
        }

        // Give remaining image-less products a random image from the pool,
        // so the storefront never shows empty cards.
        $pool = collect(File::allFiles($root))
            ->filter(fn ($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f->getFilename()))
            ->map(fn ($f) => 'products/' . $f->getRelativePath() . '/' . $f->getFilename())
            ->values();

        $stillMissing = array_diff(
            $productIds,
            $existing,
            array_unique(array_column($rows, 'product_id'))
        );

        $extra = [];
        foreach ($stillMissing as $productId) {
            if ($pool->isEmpty()) {
                break;
            }
            $extra[] = [
                'product_id' => $productId,
                'image_path' => $pool->random(),
                'alt' => null,
                'sort_order' => 1,
                'is_main' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach (array_chunk($extra, 500) as $chunk) {
            DB::table('product_images')->insert($chunk);
        }

        // Repair rows whose files are missing on disk (e.g. stale dump paths).
        $repaired = 0;
        $all = DB::table('product_images')->get(['id', 'product_id', 'image_path']);
        foreach ($all as $img) {
            if (File::exists(storage_path('app/public/' . $img->image_path))) {
                continue;
            }

            $dir = $root . DIRECTORY_SEPARATOR . $img->product_id;
            $local = File::isDirectory($dir)
                ? collect(File::files($dir))
                    ->first(fn ($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f->getFilename()))
                : null;

            if ($local) {
                DB::table('product_images')
                    ->where('id', $img->id)
                    ->update(['image_path' => 'products/' . $img->product_id . '/' . $local->getFilename()]);
            } elseif (!$pool->isEmpty()) {
                DB::table('product_images')
                    ->where('id', $img->id)
                    ->update(['image_path' => $pool->random()]);
            } else {
                DB::table('product_images')->where('id', $img->id)->delete();
            }
            $repaired++;
        }

        if ($repaired > 0) {
            $this->command->info("Repaired {$repaired} image rows with missing files.");
        }

        $this->command->info('Linked ' . (count($rows) + count($extra)) . ' product images from storage.');
    }
}
