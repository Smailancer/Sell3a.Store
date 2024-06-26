<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_product'; // Ensure this matches your actual pivot table name

    protected $fillable = [
        'order_id',
        'product_id',
        'option',
        'quantity',
        'price',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
