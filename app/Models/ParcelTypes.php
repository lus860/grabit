<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParcelTypes extends Model
{
    protected $fillable = ['id','parcel_name','parcel_status'];
}
