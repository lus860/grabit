<?php
namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait GetIncrement {
    public static function getIncrement(){
        $model = new self();
        $database = $model->getConnection()->getDatabaseName();
        $table = $model->getTable();
        if (empty(DB::select("SELECT `AUTO_INCREMENT` as `increment` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'"))){
            return 0;
        }
        return DB::select("SELECT `AUTO_INCREMENT` as `increment` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$database' AND TABLE_NAME = '$table'")[0]->increment;
    }
}