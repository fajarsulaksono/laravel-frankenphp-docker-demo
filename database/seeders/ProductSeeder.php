<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('slug', 'elektronik')->first()->id;
        $clothing = Category::where('slug', 'pakaian')->first()->id;
        $food = Category::where('slug', 'makanan-minuman')->first()->id;
        $household = Category::where('slug', 'rumah-tangga')->first()->id;
        $sports = Category::where('slug', 'olahraga')->first()->id;

        $products = [
            // Elektronik
            ['category_id' => $electronics, 'name' => 'Smartphone Pro Max', 'slug' => 'smartphone-pro-max', 'description' => 'Smartphone flagship dengan kamera 108MP dan baterai 5000mAh', 'price' => 12999000, 'stock' => 50, 'image_url' => 'https://placehold.co/400x400?text=Smartphone'],
            ['category_id' => $electronics, 'name' => 'Laptop Ultrabook 14"', 'slug' => 'laptop-ultrabook-14', 'description' => 'Laptop tipis ringan dengan prosesor terbaru, RAM 16GB, SSD 512GB', 'price' => 15999000, 'stock' => 30, 'image_url' => 'https://placehold.co/400x400?text=Laptop'],
            ['category_id' => $electronics, 'name' => 'Wireless Earbuds', 'slug' => 'wireless-earbuds', 'description' => 'TWS earbuds dengan active noise cancellation dan baterai tahan 8 jam', 'price' => 1899000, 'stock' => 100, 'image_url' => 'https://placehold.co/400x400?text=Earbuds'],
            ['category_id' => $electronics, 'name' => 'Smartwatch Series 5', 'slug' => 'smartwatch-series-5', 'description' => 'Smartwatch dengan monitor detak jantung, GPS, dan tahan air IP68', 'price' => 3499000, 'stock' => 75, 'image_url' => 'https://placehold.co/400x400?text=Smartwatch'],

            // Pakaian
            ['category_id' => $clothing, 'name' => 'Kaos Polos Premium', 'slug' => 'kaos-polos-premium', 'description' => 'Kaos katun combed 30s, nyaman dipakai sehari-hari', 'price' => 99000, 'stock' => 200, 'image_url' => 'https://placehold.co/400x400?text=Kaos'],
            ['category_id' => $clothing, 'name' => 'Jaket Hoodie Uniq', 'slug' => 'jaket-hoodie-uniq', 'description' => 'Jaket hoodie berbahan fleece tebal, cocok untuk cuaca dingin', 'price' => 249000, 'stock' => 150, 'image_url' => 'https://placehold.co/400x400?text=Hoodie'],
            ['category_id' => $clothing, 'name' => 'Sepatu Sneaker Casual', 'slug' => 'sepatu-sneaker-casual', 'description' => 'Sepatu sneaker时尚 dengan sol empuk, nyaman untuk jalan-jalan', 'price' => 399000, 'stock' => 80, 'image_url' => 'https://placehold.co/400x400?text=Sneaker'],

            // Makanan & Minuman
            ['category_id' => $food, 'name' => 'Kopi Arabika Premium 250g', 'slug' => 'kopi-arabika-premium-250g', 'description' => 'Biji kopi arabika pilihan dari dataran tinggi Gayo, roasted medium', 'price' => 85000, 'stock' => 300, 'image_url' => 'https://placehold.co/400x400?text=Kopi'],
            ['category_id' => $food, 'name' => 'Cokelat Belgia Dark 70%', 'slug' => 'cokelat-belgia-dark-70', 'description' => 'Cokelat import dari Belgia dengan kadar kakao 70%, rasa premium', 'price' => 45000, 'stock' => 250, 'image_url' => 'https://placehold.co/400x400?text=Cokelat'],
            ['category_id' => $food, 'name' => 'Madu Murni Hutan 500ml', 'slug' => 'madu-murni-hutan-500ml', 'description' => 'Madu hutan asli tanpa campuran gula, kaya antioksidan', 'price' => 120000, 'stock' => 120, 'image_url' => 'https://placehold.co/400x400?text=Madu'],

            // Rumah Tangga
            ['category_id' => $household, 'name' => 'Set Panci Stainless 5pcs', 'slug' => 'set-panci-stainless-5pcs', 'description' => 'Panci stainless steel anti karat, 5 ukuran lengkap untuk kebutuhan dapur', 'price' => 450000, 'stock' => 60, 'image_url' => 'https://placehold.co/400x400?text=Panci'],
            ['category_id' => $household, 'name' => 'Robot Vacuum Cleaner', 'slug' => 'robot-vacuum-cleaner', 'description' => 'Vacuum cleaner pintar dengan navigasi laser, bisa diatur via app', 'price' => 3499000, 'stock' => 40, 'image_url' => 'https://placehold.co/400x400?text=Vacuum'],
            ['category_id' => $household, 'name' => 'Lampu Meja LED', 'slug' => 'lampu-meja-led', 'description' => 'Lampu meja LED dengan 3 tingkat kecerahan, eye-care, USB charging port', 'price' => 175000, 'stock' => 90, 'image_url' => 'https://placehold.co/400x400?text=Lampu'],

            // Olahraga
            ['category_id' => $sports, 'name' => 'Yoga Mat Premium', 'slug' => 'yoga-mat-premium', 'description' => 'Alas yoga tebal 6mm, anti slip, nyaman untuk olahraga di rumah', 'price' => 199000, 'stock' => 110, 'image_url' => 'https://placehold.co/400x400?text=Yoga+Mat'],
            ['category_id' => $sports, 'name' => 'Dumbbell Set 20kg', 'slug' => 'dumbbell-set-20kg', 'description' => 'Set dumbbell adjustable 20kg, cocok untuk latihan kekuatan di rumah', 'price' => 599000, 'stock' => 45, 'image_url' => 'https://placehold.co/400x400?text=Dumbbell'],
            ['category_id' => $sports, 'name' => 'Sepeda Lipat 20"', 'slug' => 'sepeda-lipat-20', 'description' => 'Sepeda lipat ringan dengan 7 speed, cocok untuk commuting dan rekreasi', 'price' => 2499000, 'stock' => 25, 'image_url' => 'https://placehold.co/400x400?text=Sepeda+Lipat'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
