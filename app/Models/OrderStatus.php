<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';

    public function statuses()
    {
        return $this->belongsTo(Statuses::class, 'status_id');
    }
}
