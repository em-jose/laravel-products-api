<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test the endpoint is accesible
     *
     * @return void
     */
    public function test_products_index_is_accesible()
    {
        $response = $this->get('api/products');

        $response->assertStatus(200);
    }

    /**
     * Test if the returned JSON have 5 specific elements
     *
     * @return void
     */
    public function test_products_complete_listing()
    {
        $this->seed();

        $this->json('GET', 'api/products')
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '89000',
                            'final' => '62300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000002',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '99000',
                            'final' => '69300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '71000',
                            'final' => '49700',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000004',
                        'name' => 'Naima embellished suede sandals',
                        'category' => 'sandals',
                        'price' => [
                            'original' => '79500',
                            'final' => '79500',
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000005',
                        'name' => 'Nathane leather sneakers',
                        'category' => 'sneakers',
                        'price' => [
                            'original' => '59000',
                            'final' => '59000',
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                ],
            ]);
    }

    /**
     * Test if filtered products by category are correct
     *
     * @return void
     */
    public function test_products_listing_filtered_by_category()
    {
        $this->seed();

        $this->call('GET', 'api/products', ['category'=>'boots'])
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '89000',
                            'final' => '62300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000002',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '99000',
                            'final' => '69300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '71000',
                            'final' => '49700',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ]
                ],
            ]);
    }

    /**
     * Test if no results are returned when the user filters by non existing category
     *
     * @return void
     */
    public function test_products_listing_filtered_by_non_existing_category()
    {
        $this->seed();

        $this->call('GET', 'api/products', ['category'=>'jeans'])
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [],
            ]);
    }

    /**
     * Test if filtered products by price are correct
     *
     * @return void
     */
    public function test_products_listing_filtered_by_price_less_than()
    {
        $this->seed();

        $this->call('GET', 'api/products', ['priceLessThan'=>90000])
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '89000',
                            'final' => '62300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '71000',
                            'final' => '49700',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000004',
                        'name' => 'Naima embellished suede sandals',
                        'category' => 'sandals',
                        'price' => [
                            'original' => '79500',
                            'final' => '79500',
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000005',
                        'name' => 'Nathane leather sneakers',
                        'category' => 'sneakers',
                        'price' => [
                            'original' => '59000',
                            'final' => '59000',
                            'discount_percentage' => null,
                            'currency' => 'EUR',
                        ],
                    ]
                ]
            ]);
    }

    /**
     * Test if no results are returned when the user filters by non existing price
     *
     * @return void
     */
    public function test_products_listing_filtered_by_not_existing_price()
    {
        $this->seed();

        $this->call('GET', 'api/products', ['priceLessThan'=>10])
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [],
            ]);
    }

    /**
     * Test if filtered products by price and category are correct
     *
     * @return void
     */
    public function test_products_listing_filtered_by_price_and_category()
    {
        $this->seed();

        $this->call('GET', 'api/products', ['priceLessThan'=>90000, 'category'=>'boots'])
            ->assertStatus(200)
            ->assertExactJson([
                'products' => [
                    [
                        'sku' => '000001',
                        'name' => 'BV Lean leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '89000',
                            'final' => '62300',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ],
                    [
                        'sku' => '000003',
                        'name' => 'Ashlington leather ankle boots',
                        'category' => 'boots',
                        'price' => [
                            'original' => '71000',
                            'final' => '49700',
                            'discount_percentage' => '30%',
                            'currency' => 'EUR',
                        ],
                    ]
                ]
            ]);
    }
}
