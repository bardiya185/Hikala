<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Sara Mohammadi', 'mobile' => '09120000001'],
            ['name' => 'Ali Rezaei', 'mobile' => '09120000002'],
            ['name' => 'Maryam Hosseini', 'mobile' => '09120000003'],
            ['name' => 'Mohammad Karimi', 'mobile' => '09120000004'],
            ['name' => 'Zahra Ahmadi', 'mobile' => '09120000005'],
            ['name' => 'Hossein Jafari', 'mobile' => '09120000006'],
        ];

        $now = now();

        $rows = array_map(static fn ($u) => [
            'name' => $u['name'],
            'mobile' => $u['mobile'],
            'mobile_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], $users);

        DB::table('users')->upsert($rows, 'mobile');

        $this->command->info('Seeded ' . count($users) . ' demo users.');
    }
}
