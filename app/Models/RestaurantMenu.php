<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantMenu extends Model
{
    protected $table = 'restaurant_menu';

    /*public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }*/

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
