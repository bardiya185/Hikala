<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshCampaignWindow extends Command
{
    protected $signature = 'campaigns:window {--days=2 : Window length in days from now}';

    protected $description = 'Reset active discount campaigns to a rolling time window (starts yesterday, ends in N days)';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));

        $updated = DB::table('discount_campaigns')
            ->where('is_active', true)
            ->update([
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays($days),
                'updated_at' => now(),
            ]);

        $this->info("Refreshed {$updated} active campaigns to a {$days}-day window (ends " . now()->addDays($days)->toDateTimeString() . ').');

        return self::SUCCESS;
    }
}
