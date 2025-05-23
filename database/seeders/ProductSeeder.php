<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'code' => 'LAP001',
                'name' => 'MacBook Pro 16"',
                'model' => 'M3 Pro',
                'description' => 'Latest MacBook Pro with M3 Pro chip, 16GB RAM, 512GB SSD',
                'price' => 1999.99,
                'stock' => 10,
                'photo' => 'https://example.com/macbook.jpg'
            ],
            [
                'code' => 'PHN001',
                'name' => 'iPhone 15 Pro',
                'model' => '256GB',
                'description' => 'Latest iPhone with A17 Pro chip, 256GB storage',
                'price' => 999.99,
                'stock' => 15,
                'photo' => 'https://example.com/iphone.jpg'
            ],
            [
                'code' => 'TAB001',
                'name' => 'iPad Pro 12.9"',
                'model' => 'M2',
                'description' => 'iPad Pro with M2 chip, 12.9-inch display, 256GB storage',
                'price' => 1099.99,
                'stock' => 8,
                'photo' => 'https://example.com/ipad.jpg'
            ],
            [
                'code' => 'WAT001',
                'name' => 'Apple Watch Series 9',
                'model' => 'GPS + Cellular',
                'description' => 'Latest Apple Watch with GPS and Cellular connectivity',
                'price' => 499.99,
                'stock' => 20,
                'photo' => 'https://example.com/watch.jpg'
            ],
            [
                'code' => 'AIR001',
                'name' => 'AirPods Pro 2',
                'model' => 'USB-C',
                'description' => 'Latest AirPods Pro with USB-C charging case',
                'price' => 249.99,
                'stock' => 25,
                'photo' => 'https://example.com/airpods.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 