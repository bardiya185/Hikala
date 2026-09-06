<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TestRateLimit extends Command
{
    protected $signature = 'test:rate-limit {--count=70} {--url=/api/products}';
    protected $description = 'Test API rate limiting';

    public function handle(): int
    {
        $count = (int) $this->option('count');
        $url = rtrim(config('app.url'), '/') . $this->option('url');

        $success = 0;
        $limited = 0;
        $errors = 0;

        $this->info("Testing rate limit on: {$url}");
        $this->info("Sending {$count} requests...\n");

        for ($i = 1; $i <= $count; $i++) {
            try {
                $response = Http::timeout(5)->get($url);
                $code = $response->status();

                if ($code === 200) {
                    $success++;
                    $this->line("<fg=green>Request #{$i} -> 200 OK</>");
                } elseif ($code === 429) {
                    $limited++;
                    $this->line("<fg=red>Request #{$i} -> 429 Too Many Requests</>");
                } else {
                    $errors++;
                    $this->line("<fg=yellow>Request #{$i} -> {$code}</>");
                }
            } catch (\Throwable $e) {
                $errors++;
                $this->line("<fg=yellow>Request #{$i} -> ERROR: {$e->getMessage()}</>");
            }
        }

        $this->newLine();
        $this->info('========== RESULT ==========');
        $this->line("Success (200): {$success}");
        $this->line("Rate Limited (429): {$limited}");
        $this->line("Other Errors: {$errors}");

        return self::SUCCESS;
    }
}