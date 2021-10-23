<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\CourierOrders;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;

class Csv2 implements ShouldAutoSize,FromArray
{
    use Exportable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }


    public function array(): array
    {
        return $this->data;
    }

}
