<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiControllers;
use App\Models\NotificationsHistory;
use App\Services\AuthService;
use App\Models\StatusesHistory;
use Illuminate\Http\Request;

class NotificationController extends ApiControllers
{
    public function get_history(){
        $history = NotificationsHistory::where('user_id',AuthService::getUser()['id'])
            ->select([
                'title as notification_title',
                'message as notification_message',
                'created_at',
            ])->get();
        if ($history->count()){
            return $this->sendResponse($history,'histories');
        }
        return $this->sendError('You don\'t have any notifications to view');
    }
}
