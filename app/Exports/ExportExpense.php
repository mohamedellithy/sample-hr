<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExpense implements FromCollection ,WithHeadings
{
    private $service_filter;
    private $from;
    private $to;
    private $filter;

    public function __construct($service_filter,$from,$to,$filter)
    {
        $this->service_filter = $service_filter;
        $this->from = $from;
        $this->to = $to;
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

        if ($this->from and$this->to and $this->from != "" and $this->to != "") {

            $expenses->whereBetween('expense_date',[$this->from,$this->to]);
        }

        return $expenses->select('service','amount','expense_date')->get();
    }

    public function headings(): array
    {
        return ["service","amount","date"];
    }
}
