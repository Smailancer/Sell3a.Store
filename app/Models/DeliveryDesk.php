<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDesk extends Model
{
    use HasFactory;

        public function company()
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

        public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

        public function daira()
    {
        return $this->belongsTo(Daira::class);
    }

        public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

}
