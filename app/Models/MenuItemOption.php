<?php

namespace App\Models;

use App\Traits\GetIncrement;
use App\Traits\InsertOrUpdate;
use Illuminate\Database\Eloquent\Model;

class MenuItemOption extends Model
{
    use GetIncrement;
    use InsertOrUpdate;

    public $timestamps = false;
    protected $table = 'menu_item_options';


    public function values(){
        return $this->hasMany(MenuItemOptionValue::class, 'option_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class,'item_id','id');
    }

    public function customizationGroup()
    {
        return $this->belongsTo(CustomizationGroup::class, 'option_key');
    }

    public function customizationValue()
    {
        return $this->belongsTo(CustomizationValue::class, 'option_value');
    }

    public function optionValues()
    {
        return $this->hasMany(MenuItemOptionValue::class, 'option_id');
    }
}
