<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
