<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\MoneyResource;

class ExportMoneyResources implements FromCollection ,WithHeadings
{
    private $search;
    private $datefilter;
    private $filter;

    public function __construct($search,$datefilter,$filter)
    {
        $this->datefilter = $datefilter;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $money_resource = MoneyResource::query();

    
        $money_resource->when($this->filter  == 'sort_asc', function ($q) {
            return $q->orderBy('resource_date', 'asc');
        },function ($q) {
            return $q->orderBy('resource_date', 'desc');
        });


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $money_resource->whereBetween('resource_date',[$from,$to]);
        }

        return $money_resource->select(
            'id',
            'value',
            'type',
            'resource_date'
        )->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'value',
            'type',
            'resource_date'
        ];
    }
}
