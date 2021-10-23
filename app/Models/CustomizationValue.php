<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationValue extends Model
{
    public $timestamps = false;
    protected $table = 'customization_values';

    public function group()
    {
        return $this->belongsTo(CustomizationGroup::class);
    }

    public function menuItemOption()
    {
        return $this->hasOne(MenuItemOption::class);
    }
}
