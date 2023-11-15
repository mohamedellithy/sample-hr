<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportExpense implements FromCollection ,WithHeadings
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
        $expenses = Expense::query();

        $expenses->when($this->search != null, function ($q){
            return $q->where('section','like','%'.$this->search.'%')
            ->orWhere('sub_service','like','%'.$this->search.'%')
            ->orWhere('bill_no','like','%'.$this->search.'%')
            ->orWhere('supplier','like','%'.$this->search.'%');
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

        return $expenses->select(
            'section',
            'sub_service',
            'bill_no',
            'supplier',
            'amount',
            'paid_amount',
            'pending_amount',
            'expense_description',
            'expense_date'
        )->get();
    }

    public function headings(): array
    {
        return [
            'section',
            'sub_service',
            'bill_no',
            'supplier',
            'amount',
            'paid_amount',
            'pending_amount',
            'expense_description',
            'expense_date'
        ];
    }
}
