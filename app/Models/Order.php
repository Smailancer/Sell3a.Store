<?php

namespace App\Models;

use App\Models\Product;
use App\Models\OrderProduct;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class)
    //                 ->using(OrderProduct::class) // Specify the custom pivot model
    //                 ->withPivot(['quantity', 'price', 'options']);
    // }

    // public function orderProducts()
    // {
    //     return $this->hasMany(OrderProduct::class);
    // }

    public function orderProducts(): HasMany {
        return $this->hasMany(OrderProduct::class);
    }

    public function company()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

        public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

        public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

        public function daira()
    {
        return $this->belongsTo(Daira::class);
    }
}
