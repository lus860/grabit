<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title',
        'keyword',
        'description',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

//    public $timestamps = false;

    public static function _save_by_title($title,$data){
        $answer = self::where('title',$title)->get();
        if (!$answer->count()){
            return false;
        }
        foreach ($answer as $item){
            if (isset($data[$item->keyword]) && !empty($data[$item->keyword]) && !is_array($data[$item->keyword])){
                $item->description=$data[$item->keyword];

                if (!$item->save()){
                    return false;
                }
            }
        }
        return true;
    }

    public static function _save($request,$id=null)
    {
        if (!$item = self::find($id)) {
            $item = new self();
        }

        if (isset($request['title']) && $request['title']) {
            $item->title = $request['title'];
        }
        if (isset($request['keyword']) && $request['keyword']){
            $item->keyword = $request['keyword'];
        }
        if (isset($request['description']) && $request['description']){
            $item->description = $request['description'];
        }
        if ($item->save()){
            return $item;
        }
        return false;
    }
}