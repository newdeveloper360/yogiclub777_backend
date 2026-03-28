<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                GameTypeSeeder::class,
                UserSeeder::class,
                PermissionSeeder::class,
                AppDataSeeder::class,
            ]
        );
    }
}
