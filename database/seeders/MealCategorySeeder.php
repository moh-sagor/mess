<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MealCategory;

class MealCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Vegetarian',
                'description' => 'Meals containing no meat, poultry, or fish',
                'is_active' => true,
            ],
            [
                'name' => 'Non-Vegetarian',
                'description' => 'Meals containing meat, poultry, or fish',
                'is_active' => true,
            ],
            [
                'name' => 'Vegan',
                'description' => 'Meals containing no animal products',
                'is_active' => true,
            ],
            [
                'name' => 'Gluten-Free',
                'description' => 'Meals without gluten-containing ingredients',
                'is_active' => true,
            ],
            [
                'name' => 'Dairy-Free',
                'description' => 'Meals without dairy products',
                'is_active' => true,
            ],
            [
                'name' => 'Spicy',
                'description' => 'Meals with high spice level',
                'is_active' => true,
            ],
            [
                'name' => 'Mild',
                'description' => 'Meals with low or no spice',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            MealCategory::create($category);
        }
    }
}
