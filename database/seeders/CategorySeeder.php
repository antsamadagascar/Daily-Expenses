<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategories = [
            [
                'name' => 'Alimentation',
                'color' => '#10B981',
                'icon' => 'shopping-cart',
                'is_default' => true,
            ],
            [
                'name' => 'Transport',
                'color' => '#3B82F6',
                'icon' => 'car',
                'is_default' => true,
            ],
            [
                'name' => 'Logement',
                'color' => '#8B5CF6',
                'icon' => 'home',
                'is_default' => true,
            ],
            [
                'name' => 'Santé',
                'color' => '#EF4444',
                'icon' => 'heart',
                'is_default' => true,
            ],
            [
                'name' => 'Loisirs',
                'color' => '#F59E0B',
                'icon' => 'play',
                'is_default' => true,
            ],
            [
                'name' => 'Vêtements',
                'color' => '#EC4899',
                'icon' => 'shirt',
                'is_default' => true,
            ],
            [
                'name' => 'Éducation',
                'color' => '#06B6D4',
                'icon' => 'book',
                'is_default' => true,
            ],
            [
                'name' => 'Autres',
                'color' => '#6B7280',
                'icon' => 'more-horizontal',
                'is_default' => true,
            ],
        ];

        foreach ($defaultCategories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name'], 'is_default' => true],
                $category
            );
        }
    }
}