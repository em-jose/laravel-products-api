<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $current_date_time = Carbon::now()->toDateTimeString();

        // Populate categories table
        DB::table('categories')->insert([
            [
                "name" => "boots",
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "name" => "sandals",
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "name" => "sneakers",
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
        ]);

        // Populate products table
        DB::table('products')->insert([
            [
                "sku" => "000001",
                "name" => "BV Lean leather ankle boots",
                "category_id" => 1,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "sku" => "000002",
                "name" => "BV Lean leather ankle boots",
                "category_id" => 1,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "sku" => "000003",
                "name" => "Ashlington leather ankle boots",
                "category_id" => 1,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "sku" => "000004",
                "name" => "Naima embellished suede sandals",
                "category_id" => 2,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "sku" => "000005",
                "name" => "Nathane leather sneakers",
                "category_id" => 3,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
        ]);

        // Populate prices
        $currency = 'EUR';
        DB::table('prices')->insert([
            [
                "product_id" => 1,
                "original_price" => 890,
                "currency" => $currency,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "product_id" => 2,
                "original_price" => 990,
                "currency" => $currency,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "product_id" => 3,
                "original_price" => 710,
                "currency" => $currency,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "product_id" => 4,
                "original_price" => 795,
                "currency" => $currency,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ],
            [
                "product_id" => 5,
                "original_price" => 590,
                "currency" => $currency,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time

            ],
        ]);

        // Populate discounts
        DB::table('discount_categories')->insert([
            [
                "discount_percentage" => 30,
                "category_id" => 1,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ]
        ]);

        DB::table('discount_products')->insert([
            [
                "discount_percentage" => 15,
                "product_id" => 3,
                "created_at" => $current_date_time,
                "updated_at" => $current_date_time
            ]
        ]);
    }
}
