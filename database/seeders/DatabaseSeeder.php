<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti username/password sesuai keinginan Anda kalau belum pernah dijalankan.
        // Kalau user 'admin' sudah ada (dari seed sebelumnya), baris ini otomatis dilewati.
        if (! User::where('username', 'admin')->exists()) {
            User::factory()->create([
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@udanekarupa.test',
                'password' => bcrypt('admin123'),
            ]);
        }

        $this->call(ProductSeeder::class);
    }
}
