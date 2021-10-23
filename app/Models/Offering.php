<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    public function restaurantOffering()
    {
        return $this->belongsTo(RestaurantOffering::class);
    }
}
