<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['type_publication' => 'Emergencia'],
            ['type_publication' => 'Deslizamiento'],
            ['type_publication' => 'Incendio'],
            ['type_publication' => 'Inundación'],
            ['type_publication' => 'Otros']
        ];
//
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
