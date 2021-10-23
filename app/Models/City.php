<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function area()
    {
        return $this->hasMany(Area::class);
    }

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }
}
