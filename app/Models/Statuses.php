<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statuses extends Model
{
    protected $table = 'statuses';

    public function orderStatus()
    {
        return $this->hasOne(OrderStatus::class);
    }
}
