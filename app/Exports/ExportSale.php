<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Sale;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportSale implements FromCollection,WithHeadings
{

    private $filter;
    private $datefilter;


    public function __construct($datefilter,$filter)
    {
        $this->datefilter = $datefilter;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $sales = Sale::query();


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $sales->whereBetween('sale_date',[$from,$to]);
        }
        $sales->when( $this->filter == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

       return $sales->select('cash','bank','discount','credit_sales','sale_date')->get();


    }

    public function headings(): array
    {
        return ['كاش','كريدت','خصم','آجل','التاريخ'];
    }
}
