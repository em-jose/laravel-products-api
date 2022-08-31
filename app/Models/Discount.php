<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
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
     * Get the parent discount model (product or category).
     */
    public function discountable()
    {
        return $this->morphTo();
    }
}
