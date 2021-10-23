<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorType extends Model
{
   protected $table = 'vendor_types';


   public function RatingContent()
   {
      return $this->
         hasMany(RatingContent::class, 'rating_delivery_vendor')
         ->select('id','rating_score',
            'rating_delivery_vendor',
            'name as rating_message'
         );
   }
}
