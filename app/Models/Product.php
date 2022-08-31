<?php

namespace App\Models;

use App\Models\Price;
use App\Models\Category;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'name',
        'category'
    ];

    /**
     * Get the price associated with the product.
     */
    public function price()
    {
        return $this->hasOne(Price::class);
    }

    /**
     * Get the product that owns this price
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all of the product's discounts.
     */
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    /**
     * Get the highest discount of one product. If the value is empty return null.
     *
     * @return int || null
     */
    public function getBiggestPercentageDiscount()
    {
        $product_discounts = $this->discounts;
        $category_discounts = $this->category->discounts;
        $all_discounts = $product_discounts->merge($category_discounts);

        return $all_discounts->max('discount_percentage');
    }
}
