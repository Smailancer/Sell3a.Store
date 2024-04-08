<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'options' => 'array',
        'image' => 'array',
    ];

    public function store()
        {
            return $this->belongsTo(Store::class);
        }

        public function orders()
        {
            return $this->belongsToMany(Order::class)
                        ->using(OrderProduct::class)
                        ->withPivot(['quantity', 'price','options']);
        }
}
