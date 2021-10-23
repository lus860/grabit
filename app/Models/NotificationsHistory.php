<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class NotificationsHistory extends Model
{
    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }
        if (isset($request['user_id']) && $request['user_id']) {
            $item->user_id = $request['user_id'];
        }
        if (isset($request['title']) && $request['title']) {
            $item->title = $request['title'];
        }
        if (isset($request['message']) && $request['message']) {
            $item->message = $request['message'];
        }
        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    public function getCreatedAtAttribute($value){
        return Carbon::parse($value)->toDateTimeString();
    }
}
