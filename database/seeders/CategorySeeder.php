<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Beddings',
                'slug' => 'beddings',
                'description' => 'Luxury hotel-grade bedding and linens'
            ],
            [
                'name' => 'Kitchenware',
                'slug' => 'kitchenware',
                'description' => 'Professional kitchen equipment and dining ware'
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Elegant furniture for hotel rooms and common areas'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
