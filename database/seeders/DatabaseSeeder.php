<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Populate categories table
        DB::table('categories')->insert([
            [
                "name" => "boots"
            ],
            [
                "name" => "sandals"
            ],
            [
                "name" => "sneakers"
            ],
        ]);

        // Populate products table
        DB::table('products')->insert([
            [
                "sku" => "000001",
                "name" => "BV Lean leather ankle boots",
                "category_id" => 1,
            ],
            [
                "sku" => "000002",
                "name" => "BV Lean leather ankle boots",
                "category_id" => 1,
            ],
            [
                "sku" => "000003",
                "name" => "Ashlington leather ankle boots",
                "category_id" => 1,
            ],
            [
                "sku" => "000004",
                "name" => "Naima embellished suede sandals",
                "category_id" => 2,
            ],
            [
                "sku" => "000005",
                "name" => "Nathane leather sneakers",
                "category_id" => 3,
            ],
        ]);

        // Populate prices
        $currency = 'EUR';
        DB::table('prices')->insert([
            [
                "product_id" => 1,
                "original_price" => 89000,
                "currency" => $currency
            ],
            [
                "product_id" => 2,
                "original_price" => 99000,
                "currency" => $currency
            ],
            [
                "product_id" => 3,
                "original_price" => 71000,
                "currency" => $currency
            ],
            [
                "product_id" => 4,
                "original_price" => 79500,
                "currency" => $currency
            ],
            [
                "product_id" => 5,
                "original_price" => 59000,
                "currency" => $currency

            ],
        ]);

        // Populate discounts
        DB::table('discounts')->insert([
            [
                "discount_percentage" => 30,
                "discountable_type" => 'App\Models\Category',
                "discountable_id" => 1
            ],
            [
                "discount_percentage" => 15,
                "discountable_type" => 'App\Models\Product',
                "discountable_id" => 3
            ]
        ]);
    }
}
