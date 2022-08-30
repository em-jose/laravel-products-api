<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Price extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
