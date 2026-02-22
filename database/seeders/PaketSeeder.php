<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pakets')->insert([
            [
                'nama' => 'Paket 1',
                'durasi' => 30,
                'harga' => 100000,
                'deskripsi' => 'Akses penuh selama 30 hari',
            ],
            [
                'nama' => 'Paket 2',
                'durasi' => 90,
                'harga' => 250000,
                'deskripsi' => 'Akses penuh selama 90 hari',
            ],
        ]);
    }
}
