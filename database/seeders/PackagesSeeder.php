<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagesSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('packages')->insert([
            [
                'name' => 'Paket Dasar',
                'price' => 49000,
                'duration_days' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Medium',
                'price' => 235000,
                'duration_days' => 180,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Paket Ultimate',
                'price' => 450000,
                'duration_days' => 360,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
