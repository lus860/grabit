<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteRestaurant extends Model
{
    public $timestamps = false;
    protected $table = 'favourite_restaurants';

    public function get_vendor()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
