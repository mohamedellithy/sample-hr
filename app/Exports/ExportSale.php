<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSale implements FromCollection
{

    private $filter;
    private $from;
    private $to;

    public function __construct($from,$to,$filter)
    {
        $this->from = $from;
        $this->to = $to;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sales = Sale::query();


        if ($this->from and $this->to and $this->from != "" and $this->to != "") {

            $sales->whereBetween('sale_date',[$this->from,$this->to]);
        }

        $sales->when( $this->filter == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

       return $sales->get();


    }
}
