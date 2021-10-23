<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantOpeningTime extends Model
{
//    public $timestamps = false;
    protected $table = 'restaurant_opening_times';

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
