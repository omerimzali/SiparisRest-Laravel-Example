<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JsonProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(base_path('products.json'));
$products = json_decode($json, true);

foreach ($products as $product) {
    \App\Models\Product::updateOrCreate(
        ['id' => $product['id']],
        [
            'name' => $product['name'],
            'category' => $product['category'],
            'price' => $product['price'],
            'stock' => $product['stock'],
        ]
    );
}
    }
}
