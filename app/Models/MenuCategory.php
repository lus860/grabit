<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    protected $table = 'menu_categories';
    public $timestamps = false;

    public function menu()
    {
        return $this->hasOne(Menu::class, 'menu_category_id');
    }
}
