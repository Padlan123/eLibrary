<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Fiksi'],
            ['name' => 'Non-Fiksi'],
            ['name' => 'Sains'],
            ['name' => 'Teknologi'],
            ['name' => 'Sci-Fi'],
            ['name' => 'Romantis'],
            ['name' => 'Aksi'],
            ['name' => 'Psikologi'],
            ['name' => 'Sejarah'],
            ['name' => 'Biografi'],
            ['name' => 'Politik'],
            ['name' => 'Ekonomi'],
            ['name' => 'Sosial'],
            ['name' => 'Agama'],
            ['name' => 'Filsafat'],
            ['name' => 'Kesehatan'],
            ['name' => 'Olahraga'],
            ['name' => 'Hiburan'],
            ['name' => 'Pendidikan'],
            ['name' => 'Budaya'],
        ]);
    }
}
