<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomizationGroup extends Model
{
    public $timestamps = false;
    protected $table = 'customization_groups';

    public function getCtype()
    {
        return $this->ctype == 1 ? 'Multiple Select (Addon)' : 'Single Select (Variant)';
    }

    public function values()
    {
        return $this->hasMany(CustomizationValue::class, 'group_id');
    }

    public function menuItemOption()
    {
        return $this->hasOne(MenuItemOption::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
