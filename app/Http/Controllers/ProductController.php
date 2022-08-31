<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = $this->getProducts($request);
        $result = $this->processProducts($products);

        return $result;
    }

    /**
     * Get the requested products and filter them if it is necessary.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProducts(Request $request)
    {
        $query = DB::table('products')
            ->select([
                'products.id',
                'products.sku',
                'products.name',
                'products.category_id',
                'categories.name AS category_name',
                'prices.original_price',
                'prices.currency'])
            ->join(
                'categories',
                'categories.id',
                '=',
                'products.category_id',
                'left')
            ->join(
                'prices',
                'prices.product_id',
                '=',
                'products.id',
                'left');

        $category_filter = $request->query('category');

        if (!is_null($category_filter)) {
            $query->where('categories.name', '=', $category_filter);
        }

        $price_less_than_filter = $request->query('priceLessThan');

        if (!is_null($price_less_than_filter)) {
            $query->where('prices.original_price', '<=', $price_less_than_filter);
        }

        $products = $query->take(5)->get();

        return $products;
    }

    /**
     * Structure the products with all their data.
     *
     * @return array
     */
    public function processProducts(Collection $products)
    {
        $products_array = [
            'products' => []
        ];

        if ($products->isEmpty()) {
            return $products_array;
        }

        foreach ($products as $product) {
            $discount_percentage = $this->getHighestPercentageDiscount($product->id);
            $original_price = $product->original_price;
            $final_price = $original_price;

            if ($discount_percentage) {
                $final_price = $this->getDiscountedPrice(
                    $discount_percentage,
                    $original_price
                );

                $discount_percentage = $this->formatDiscount($discount_percentage);
            }

            $products_array['products'][] = [
                'sku' => $product->sku,
                'name' => $product->name,
                'category' => $product->category_name,
                'price' => [
                    'original' => $original_price,
                    'final' => $final_price,
                    'discount_percentage' => $discount_percentage,
                    'currency' => 'EUR'
                ]
            ];
        }

        return $products_array;
    }

    /**
     * Apply a percentage discount to a price.
     *
     * @return int
     */
    public function getDiscountedPrice(int $discount_percentage, int $original_price)
    {
        $total_discount = (int) ($original_price) * ($discount_percentage / 100);
        $final_price = (int) $original_price - $total_discount;

        return $final_price;
    }

    /**
     * Get the highest discount of one product. If the value is empty return null.
     *
     * @return int || null
     */
    public function getHighestPercentageDiscount(int $product_id)
    {
        $query = DB::table('products')
            ->selectRaw('
                    MAX(discount_products.discount_percentage) as product_discount,
                    MAX(discount_categories.discount_percentage) as category_discount')
            ->join(
                'discount_categories',
                'discount_categories.id',
                '=',
                'products.category_id',
                'left')
            ->join(
                'discount_products',
                'discount_products.product_id',
                '=',
                'products.id',
                'left')
            ->where('products.id', '=', $product_id);

        $discounts = $query->first();

        $highest_discount = max($discounts->product_discount, $discounts->category_discount);

        return $highest_discount;
    }

    /**
     * Format a given discount adding the "%" character
     *
     * return string
     */
    public function formatDiscount(int $discount_percentage)
    {
        return sprintf('%d%%', $discount_percentage);
    }
}
