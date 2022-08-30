<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * Get the category products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the category's discounts.
     */
    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }
}
