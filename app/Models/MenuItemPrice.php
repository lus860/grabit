<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemPrice extends Model
{
    public $timestamps = false;
    protected $table = 'menu_item_prices';

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
