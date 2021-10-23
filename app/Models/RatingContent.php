<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingContent extends Model
{
    protected $table='ratings_content';


   public function Vendor()
   {
      return $this->belongsTo(VendorType::class,'rating_delivery_vendor')->withDefault();
   }
}
