<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{

    protected $fillable=['type','from', 'to', 'message','file', 'read '];

    public static function _save($request, $id = null)
    {
        if ($id) {
            if (!$item = self::find($id)) {
                return ['success' => false, 'data' => config('errors.errors_save')['error_id']];
            }
        } else {
            $item = new self();
        }

        if (isset($request['type']) && $request['type']) {
            $item->type = $request['type'];
        }

        if (isset($request['from']) && $request['from']) {
            $item->from = $request['from'];
        }

        if (isset($request['to']) && $request['to']) {
            $item->to = $request['to'];
        }

        if (isset($request['message']) && $request['message']) {
            $item->message  = $request['message'];
        }

        if (isset($request['file']) && $request['file']) {
            $item->file = $request['file'];
        }

        if (isset($request['read ']) && $request['read']) {
            $item->file = $request['read '];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }

    public function user(){
        return $this->hasMany('App\User', 'id', 'from');
    }
}
