<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Price;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Populate categories table
        Category::factory(3)->create();

        // Populate products and prices tables
        $categories_ids = DB::table('categories')->pluck('id');

        for ($i=0; $i < 2000; $i++) {
            $product = new Product();
            $product->sku = fake()->numerify('######');
            $product->name = fake()->words(3, true);
            $product->category_id = fake()->randomElement($categories_ids);
            $product->save();

            $price = new Price();
            $price->product_id = $product->id;
            $price->original_price = fake()->numerify('######');
            $price->currency = 'EUR';
            $price->save();
        }

        // Populate discounts table
        $products_ids = DB::table('products')->pluck('id');

        for ($i=0; $i < 2; $i++) {
            $category_discount = new Discount();
            $category_discount->discount_percentage = fake()->biasedNumberBetween(10, 90);
            $category_discount->discountable_type = 'App\Models\Category';
            $category_discount->discountable_id = fake()->randomElement($categories_ids);
            $category_discount->save();
        }

        for ($i=0; $i < 15; $i++) {
            $product_discount = new Discount();
            $product_discount->discount_percentage = fake()->biasedNumberBetween(10, 90);
            $product_discount->discountable_type = 'App\Models\Product';
            $product_discount->discountable_id = fake()->randomElement($products_ids);
            $product_discount->save();
        }

    }
}
