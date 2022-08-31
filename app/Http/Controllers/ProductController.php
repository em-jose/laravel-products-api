<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::take(5)->get();

        $products_array = [
            'products' => []
        ];

        foreach ($products as $product) {
            $discount_percentage = $product->getBiggestPercentageDiscount();
            $original_price = $product->price->original_price;
            $final_price = $original_price;

            if ($discount_percentage) {
                $final_price = $this->getDiscountedPrice(
                    $discount_percentage,
                    $original_price
                );
            }

            $products_array['products'][] = [
                'sku' => $product->sku,
                'name' => $product->name,
                'category' => $product->category->name,
                'price' => [
                    'original' => $original_price,
                    'final' => $final_price,
                    'discount_percentage' => $discount_percentage,
                    'currency' => 'EUR'
                ]
            ];
        }

        return response()->json($products_array);
    }

    /**
     * Apply a percentage discount to a price
     *
     * @return int
     */
    public function getDiscountedPrice(int $discount_percentage, int $original_price)
    {
        $total_discount = (int) ($original_price) * ($discount_percentage / 100);
        $final_price = (int) $original_price - $total_discount;

        return $final_price;
    }
}
