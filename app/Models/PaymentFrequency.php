<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentFrequency extends Model
{
    public $timestamps = false;
    public $table = 'payment_frequencies';

    public function restaurant()
    {
        return $this->hasOne(Restaurant::class);
    }
}
