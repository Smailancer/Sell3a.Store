<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Daira extends Model
{
    use HasFactory;

        public function wilaya()
    {
        return $this->belongsTo(Wilaya::class);
    }

        public function communes()
    {
        return $this->hasMany(Commune::class);
    }
}
