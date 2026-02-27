<?php

namespace Database\Seeders;


use App\Models\User;
use Database\Seeders\BookCategoriesSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\CategoriesSeeder;
use Database\Seeders\PaketSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategoriesSeeder::class,
            // BookSeeder::class,
            // BookCategoriesSeeder::class,
            PackagesSeeder::class,
        ]);

        $anggota = User::create([
            'username' => 'Anggota',
            'email' => 'anggota123@gmail.com',
            'password' => bcrypt('anggota123'),
        ]);
        $admin = User::create([
            'username' => 'Admin',
            'email' => 'admin123@gmail.com',
            'password' => bcrypt('admin123'),
        ]);
        $anggota->assignRole('anggota');
        $admin->assignRole('admin');
    }
}
