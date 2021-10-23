<?php


namespace App\Traits;


use App\Models\Setting;
use Carbon\Carbon;

trait TimeTrack
{
    public static function create($prep_time){
        $prep_time_seconds = $prep_time*60;
        $delivery_time = Setting::where('keyword','delivery_time')->first()->description*60;
        $time = Carbon::now()->addSeconds($prep_time_seconds+$delivery_time);
        return $time->toDateTimeString();
    }

    public static function check($due_in){
        return Carbon::now()->gt($due_in);
    }
}