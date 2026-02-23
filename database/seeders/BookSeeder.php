<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'judul' => 'The Great Gatsby',
                'penulis' => 'F. Scott Fitzgerald',
                'penerbit' => 'Scribner',
                'tahun_terbit' => 1925,
                'sinopsis' => 'A novel about the American dream and the decadence of the Jazz Age.',
                'premium' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'To Kill a Mockingbird',
                'penulis' => 'Harper Lee',
                'penerbit' => 'J.B. Lippincott & Co.',
                'tahun_terbit' => 1960,
                'sinopsis' => 'A story of racial injustice and moral growth in the Deep South.',
                'premium' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
