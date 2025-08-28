<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\AdminSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call AdminSeeder first
        $this->call(AdminSeeder::class);
        
        // Get the admin user that was created by AdminSeeder
        $admin = User::where('email', 'admin@teman.com')->first();

        // Create sample products
        $product1 = Product::create([
            'name' => 'Premium T-Shirt',
            'description' => 'High-quality cotton t-shirt with comfortable fit',
            'price' => 29.99,
            'created_by' => $admin->id,
        ]);

        $product1->variants()->createMany([
            ['variant_name' => 'Small', 'stock' => 50],
            ['variant_name' => 'Medium', 'stock' => 75],
            ['variant_name' => 'Large', 'stock' => 60],
            ['variant_name' => 'X-Large', 'stock' => 40],
        ]);

        $product2 = Product::create([
            'name' => 'Wireless Headphones',
            'description' => 'Bluetooth headphones with noise cancellation',
            'price' => 89.99,
            'created_by' => $admin->id,
        ]);

        $product2->variants()->createMany([
            ['variant_name' => 'Black', 'stock' => 30],
            ['variant_name' => 'White', 'stock' => 25],
            ['variant_name' => 'Blue', 'stock' => 20],
        ]);

        $product3 = Product::create([
            'name' => 'Smartphone Case',
            'description' => 'Durable protective case for smartphones',
            'price' => 19.99,
            'created_by' => $admin->id,
        ]);

        $product3->variants()->createMany([
            ['variant_name' => 'iPhone 13', 'stock' => 100],
            ['variant_name' => 'iPhone 14', 'stock' => 80],
            ['variant_name' => 'Samsung Galaxy', 'stock' => 90],
        ]);

        $product4 = Product::create([
            'name' => 'Coffee Mug',
            'description' => 'Ceramic coffee mug with handle',
            'price' => 12.99,
            'created_by' => $admin->id,
        ]);

        $product4->variants()->createMany([
            ['variant_name' => 'Red', 'stock' => 200],
            ['variant_name' => 'Blue', 'stock' => 180],
            ['variant_name' => 'Green', 'stock' => 150],
        ]);
    }
}
