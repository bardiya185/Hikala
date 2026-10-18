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

        // Drop any row that does not point at the product's own folder or whose
        // file is missing on disk (stale dump paths, old random fills).
        $all = DB::table('product_images')->get(['id', 'product_id', 'image_path']);
        $removed = 0;
        foreach ($all as $img) {
            $ownPrefix = 'products/' . $img->product_id . '/';
            $valid = str_starts_with($img->image_path, $ownPrefix)
                && File::exists(storage_path('app/public/' . $img->image_path));
            if (!$valid) {
                DB::table('product_images')->where('id', $img->id)->delete();
                $removed++;
            }
        }
        if ($removed > 0) {
            $this->command->info("Removed {$removed} image rows not matching their product.");
        }

        // Drop duplicate rows pointing at the same file for one product.
        DB::statement('
            DELETE pi FROM product_images pi
            JOIN product_images pj
              ON pj.product_id = pi.product_id
             AND pj.image_path = pi.image_path
             AND pj.id < pi.id
        ');

        // Link every file from each product's own folder.
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

        $this->command->info('Linked ' . count($rows) . ' product images from their own folders.');

        // Products without their own files borrow images from a same-title
        // product that has them, so every card shows a matching picture.
        $stillMissing = array_diff(
            $productIds,
            DB::table('product_images')->pluck('product_id')->unique()->all()
        );

        $borrowed = [];
        foreach ($stillMissing as $productId) {
            $title = DB::table('products')->where('id', $productId)->value('title');
            if (!$title) {
                continue;
            }

            $donorId = DB::table('products')
                ->where('title', $title)
                ->where('id', '!=', $productId)
                ->whereIn('id', DB::table('product_images')->pluck('product_id'))
                ->orderBy('id')
                ->value('id');
            if (!$donorId) {
                continue;
            }

            $donorImages = DB::table('product_images')
                ->where('product_id', $donorId)
                ->orderBy('sort_order')
                ->get(['image_path', 'alt']);

            foreach ($donorImages as $i => $img) {
                $borrowed[] = [
                    'product_id' => $productId,
                    'image_path' => $img->image_path,
                    'alt' => $img->alt,
                    'sort_order' => $i + 1,
                    'is_main' => $i === 0 ? 1 : 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        foreach (array_chunk($borrowed, 500) as $chunk) {
            DB::table('product_images')->insert($chunk);
        }

        $this->command->info('Borrowed images for ' . count(array_unique(array_column($borrowed, 'product_id'))) . ' same-title products.');

        $missingFiles = 0;
        foreach (DB::table('product_images')->pluck('image_path') as $path) {
            if (!File::exists(storage_path('app/public/' . $path))) {
                $missingFiles++;
            }
        }
        $without = count(array_diff(
            $productIds,
            DB::table('product_images')->pluck('product_id')->unique()->all()
        ));
        $this->command->info("Verify: {$missingFiles} rows with missing files, {$without} products without images.");
    }
}
