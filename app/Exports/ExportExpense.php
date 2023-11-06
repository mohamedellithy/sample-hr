<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExpense implements FromCollection ,WithHeadings
{
    private $service_filter;
    private $datefilter;
    private $filter;

    public function __construct($service_filter,$datefilter,$filter)
    {
        $this->service_filter = $service_filter;
        $this->datefilter = $datefilter;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $expenses = Expense::query();

        $expenses->when($this->service_filter != null, function ($q) {
            return $q->where('service',$this->service_filter);
        });


        $expenses->when($this->filter  == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $expenses->whereBetween('expense_date',[$from,$to]);
        }

        return $expenses->select('service','amount','expense_date')->get();
    }

    public function headings(): array
    {
        return ["service","amount","date"];
    }
}
