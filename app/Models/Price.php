<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'original_price',
        'currency'
    ];

    /**
     * Get the product that owns this price
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
