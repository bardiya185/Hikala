<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryDumpSeeder extends Seeder
{
    public function run(): void
    {
        $dump = database_path('data/categories.sql');

        if (!is_file($dump)) {
            $this->command->warn('categories.sql dump not found, skipping.');
            return;
        }

        DB::unprepared(file_get_contents($dump));
        $this->command->info('Imported categories dump (names, icons, banners).');
    }
}
