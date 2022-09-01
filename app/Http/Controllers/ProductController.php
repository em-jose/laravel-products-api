<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

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

        return $products;
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
                'prices.currency',
                'discount_categories.discount_percentage as category_discount',
                'discount_products.discount_percentage as product_discount'])
            ->leftJoin(
                'categories',
                'categories.id',
                '=',
                'products.category_id'
            )
            ->leftJoin(
                'prices',
                'prices.product_id',
                '=',
                'products.id'
            )
            ->leftJoin('discount_categories', function ($join) {
                $join->on(
                    'discount_categories.id',
                    '=',
                    'products.category_id'
                )->where(
                    'discount_categories.discount_percentage',
                    '=',
                    DB::raw('(SELECT MAX(discount_categories.discount_percentage) FROM discount_categories dc)')
                );
            })
            ->leftJoin('discount_products', function ($join) {
                $join->on(
                    'discount_products.product_id',
                    '=',
                    'products.id'
                )->where(
                    'discount_products.discount_percentage',
                    '=',
                    DB::raw('(SELECT MAX(discount_products.discount_percentage) FROM discount_products dp)')
                );
            });

        $this->applySearchFilters($query, $request);

        $products_array = [
            'products' => []
        ];

        $query->orderBy('id')->chunk(100, function ($products) use (&$products_array) {
            if ($products->isEmpty()) {
                return false;
            }

            $limit = 5;
            $count = 1;

            foreach ($products as $product) {
                $products_array['products'][] = $this->processProducts($product);

                if ($limit == $count) {
                    return false;
                }

                $count++;
            }
        });

        return $products_array;
    }

    /**
     * Structure the products with all their data.
     *
     * @return array
     */
    public function processProducts($product)
    {
        $discount_percentage = $this->getHighestPercentageDiscount(
            $product->product_discount,
            $product->category_discount
        );
        $original_price = $product->original_price;
        $final_price = $original_price;

        if ($discount_percentage) {
            $final_price = $this->getDiscountedPrice(
                $discount_percentage,
                $original_price
            );

            $discount_percentage = $this->formatDiscount($discount_percentage);
        }

        $processed_product = [
            'sku' => $product->sku,
            'name' => $product->name,
            'category' => $product->category_name,
            'price' => [
                'original' => number_format($original_price, 2, '', ''),
                'final' => number_format($final_price, 2, '', ''),
                'discount_percentage' => $discount_percentage,
                'currency' => 'EUR'
            ]
        ];

        return $processed_product;
    }

    /**
     * Apply to the query the filters submitted by the user
     *
     * @return void
     */
    public function applySearchFilters(Builder &$query, Request $request)
    {
        $category_filter = $request->query('category');

        if (!is_null($category_filter)) {
            $query->where('categories.name', '=', $category_filter);
        }

        $price_less_than_filter = (int) $request->query('priceLessThan');

        if (!is_null($price_less_than_filter)) {
            $query->where('prices.original_price', '<=', $this->formatPriceReverse($price_less_than_filter));
        }
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
    public function getHighestPercentageDiscount($product_discount, $category_discount)
    {
        $highest_discount = max($product_discount, $category_discount);

        return $highest_discount;
    }

    /**
     * Format price from 111,11 to 11111
     */
    public function formatPrice(int $price)
    {
        return number_format($price, 2, '', '');
    }

    /**
     * Format price from 11111 to 111,11
     */
    public function formatPriceReverse(int $price)
    {
        return ($price / 100);
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
