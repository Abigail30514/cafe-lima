<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [

            ['nombre' => 'Desayunos'],
            ['nombre' => 'Sánguches'],
            ['nombre' => 'Ensaladas'],
            ['nombre' => 'Fondos'],
            ['nombre' => 'Pizzas'],
            ['nombre' => 'Pastas'],
            ['nombre' => 'Snacks'],
            ['nombre' => 'Postres'],
            ['nombre' => 'Bebidas'],

        ];

        foreach ($categorias as $categoria) {

            Category::create($categoria);

        }
    }
}