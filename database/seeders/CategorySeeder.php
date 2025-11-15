<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Work',
                'color' => '#FF5733',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Personal',
                'color' => '#33FF57',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Health',
                'color' => '#3357FF',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Education',
                'color' => '#FF33F5',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
            [
                'name' => 'Shopping',
                'color' => '#F5FF33',
                'created_by' => 'system',
                'updated_by' => 'system',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
