<?php

namespace App\Imports;

use App\CourierOrders;
use Maatwebsite\Excel\Concerns\ToModel;

class Csv implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new CourierOrders([
            //
        ]);
    }
}
