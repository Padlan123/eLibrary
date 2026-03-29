<?php

namespace Database\Seeders;

use App\Models\User;
// use Database\Seeders\BookCategoriesSeeder;
// use Database\Seeders\BookSeeder;
use Database\Seeders\CategoriesSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'anggota']);
        Permission::firstOrCreate([
            'name' => 'premium',
            'guard_name' => 'web'
        ]);

        $this->call([
            CategoriesSeeder::class,
            // BookSeeder::class,
            // BookCategoriesSeeder::class,
            PackagesSeeder::class,
        ]);

        $padlan = User::create([
            'username' => 'Padlan padilah',
            'email' => 'padlan123@gmail.com',
            'password' => bcrypt('padlan123'),
        ]);
        $padlan->assignRole('anggota');
        $guntur = User::create([
            'username' => 'guntur irawan',
            'email' => 'guntur123@gmail.com',
            'password' => bcrypt('guntur123'),
        ]);
        $guntur->assignRole('anggota');
        $eka = User::create([
            'username' => 'eka wira',
            'email' => 'eka123@gmail.com',
            'password' => bcrypt('ekaw123456'),
        ]);
        $eka->assignRole('anggota');
        $rizki = User::create([
            'username' => 'muhammad rizki',
            'email' => 'rizki123@gmail.com',
            'password' => bcrypt('rizki123'),
        ]);
        $rizki->assignRole('anggota');
        $hendra = User::create([
            'username' => 'hendra gunawan',
            'email' => 'hendra123@gmail.com',
            'password' => bcrypt('hendra123'),
        ]);
        $hendra->assignRole('anggota');
        $admin = User::create([
            'username' => 'Alex',
            'email' => 'alex123@gmail.com',
            'password' => bcrypt('alex123'),
        ]);
        $admin->assignRole('admin');

        // User::factory()->anggota()->count(10)->create();
    }
}
