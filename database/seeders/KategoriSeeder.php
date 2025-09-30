<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'nama_kategori' => 'Futsal',
        ]);
        Kategori::create([
            'nama_kategori' => 'Bola',
        ]);
        Kategori::create([
            'nama_kategori' => 'Running',
        ]);
        Kategori::create([
            'nama_kategori' => 'Sneakers',
        ]);
        Kategori::create([
            'nama_kategori' => 'Boots',
        ]);
        Kategori::create([
            'nama_kategori' => 'Lofers',
        ]);
    }
}
