<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebImages extends Model
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

        if (isset($request['page']) && $request['page']) {
            $item->page = $request['page'];
        }
        if (isset($request['name']) && $request['name']) {
            $item->name = $request['name'];
        }
        if (isset($request['description']) && $request['description']) {
            $item->description = $request['description'];
        }
        if (isset($request['saved_image']) && $request['saved_image']) {
            $item->image = $request['saved_image'];
        }

        if ($item->save()) {
            return ['success' => true, 'data' => $item];
        }
        return ['success' => false, 'data' => config('errors.errors_save')['error_conn']];
    }
}
