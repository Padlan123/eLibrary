<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin123@gmail.com',
            'password' => Hash::make('padlan123')
        ]);

        $admin->assignRole('admin');

        $admin = User::create([
            'username' => 'anggota',
            'email' => 'anggota123@gmail.com',
            'password' => Hash::make('anggota123')
        ]);

        $admin->assignRole('anggota');
    }
}
