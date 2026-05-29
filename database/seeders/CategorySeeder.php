<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'description' => 'Smartphone, laptop, aksesoris gadget dan perangkat elektronik lainnya'],
            ['name' => 'Pakaian', 'slug' => 'pakaian', 'description' => 'Baju, celana, jaket, dan fashion pria & wanita'],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'description' => 'Cemilan, minuman segar, dan bahan makanan berkualitas'],
            ['name' => 'Rumah Tangga', 'slug' => 'rumah-tangga', 'description' => 'Peralatan dapur, kebersihan, dan dekorasi rumah'],
            ['name' => 'Olahraga', 'slug' => 'olahraga', 'description' => 'Alat olahraga, sepatu, dan perlengkapan fitness'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
