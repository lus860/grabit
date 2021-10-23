<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $hidden = ['pivot'];

    public static function adminList(){
        return self::query()->get();
    }

    public static function getIds(){
        return self::query()->pluck('id')->toArray();
    }
}
