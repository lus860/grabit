<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }

    public function restaurantServiceArea()
    {
        return $this->hasOne(RestaurantServiceArea::class);
    }

    public function address()
    {
        $this->hasOne(Address::class);
    }
}
