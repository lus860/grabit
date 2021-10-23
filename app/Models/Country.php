<?php

namespace App\Models;

use App\Models\Address;
use App\Models\City;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'code',
        'name',
        'vat',
        'images',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    public $timestamps = false;

    public function restaurant(){
        return $this->hasOne(Restaurant::class);
    }
    public function city(){
        return $this->hasOne(City::class);
    }
    public function address(){
        return $this->hasOne(Address::class);
    }
}
