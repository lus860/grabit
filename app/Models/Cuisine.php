<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuisine extends Model
{
    public $timestamps = false;

    public function restaurantCuisine()
    {
        return $this->hasOne(RestaurantCuisine::class);
    }

    public function menu()
    {
        return $this->hasMany(Menu::class);
    }
}
