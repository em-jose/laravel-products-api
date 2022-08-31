<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

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
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getProducts(Request $request)
    {
        $category_filter = $request->query('category');
        $price_less_than_filter = $request->query('priceLessThan');

        $products = (new Product())
            ->when($category_filter, function ($query, $category_filter) {
                $query->join('categories', 'categories.id', '=', 'products.category_id');
                $query->where('categories.name', '=', $category_filter);
            })
            ->when($price_less_than_filter, function ($query, $price_less_than_filter) {
                $query->join('prices', 'prices.product_id', '=', 'products.id');
                $query->where('prices.original_price', '<=', $price_less_than_filter);
            })
            ->get();

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
}
