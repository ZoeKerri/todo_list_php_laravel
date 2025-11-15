<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
        ]);

        User::create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('Admin123@'),
            'full_name' => 'Test User',
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);
    }
}
