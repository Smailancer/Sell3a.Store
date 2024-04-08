<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    use HasFactory;

        public function desks()
    {
        return $this->hasMany(DeliveryDesk::class);
    }
}
