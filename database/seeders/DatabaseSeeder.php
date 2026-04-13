<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Gọi file SystemSeeder của bạn ở đây
        $this->call([
            SystemSeeder::class,
        ]);
    }
}