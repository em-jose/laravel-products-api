<?php

namespace App\Models;

use App\Models\Product;
use App\Models\DiscountCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the category products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the category discounts.
     */
    public function discounts()
    {
        return $this->hasMany(DiscountCategory::class);
    }
}
