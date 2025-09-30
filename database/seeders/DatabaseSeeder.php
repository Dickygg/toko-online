<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\kategori;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        user::create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
            'role' => '0',
            'status' => 1,
            'hp' => '123456789101',
            'password' => bcrypt('P@55word'),
        ]);

        user::create([
            'nama' => 'super admin',
            'email' => 'admin@example.com',
            'role' => '1',
            'status' => 1,
            'hp' => '123456789101',
            'password' => bcrypt('P@55word'),
        ]);
    }
}
