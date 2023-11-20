<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ExpensesPayment;

class ExportExpensePayments implements FromCollection ,WithHeadings
{
    private $search;
    private $datefilter;
    private $filter;

    public function __construct($search,$datefilter,$filter)
    {
        $this->search = $search;
        $this->datefilter = $datefilter;
        $this->filter = $filter;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $expense_payments = ExpensesPayment::query();

    
        $expense_payments->when($this->filter  == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $expense_payments->whereBetween('expense_date',[$from,$to]);
        }

        return $expense_payments->select(
            'id',
            'value',
            'created_at'
        )->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'value',
            'date'
        ];
    }
}
