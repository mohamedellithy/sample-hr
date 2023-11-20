<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;

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

        $expenses->with('department_sub:department_name','department_main:department_name');
        $expenses->when($this->search != null, function ($q){
            return $q->whereHas('department_main',function($query){
                $query->where('department_expenses.department_name','like','%'.$this->search.'%');
            })
            ->orWhereHas('department_sub',function($query){
                $query->where('department_expenses.department_name','like','%'.$this->search.'%');
            })
            ->orWhere('bill_no','like','%'.$this->search.'%')
            ->orWhere('supplier','like','%'.$this->search.'%');
        });


        $expenses->when($this->filter  == 'sort_asc', function ($q) {
            return $q->orderBy('expenses.created_at', 'asc');
        },function ($q) {
            return $q->orderBy('expenses.created_at', 'desc');
        });


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $expenses->whereBetween('expense_date',[$from,$to]);
        }

        $expenses->Join('expenses_payments','expenses_payments.expense_id','=','expenses.id');
        $expenses->LeftJoin('department_expenses as sections','sections.id','=','expenses.section');
        $expenses->LeftJoin('department_expenses as sub_services','sub_services.id','=','expenses.sub_service');

        return $expenses->select(
            'sections.department_name as section',
            'sub_services.department_name as sub_service',
            'bill_no',
            'supplier',
            'amount',
            DB::raw('sum(expenses_payments.value) as paid_amount'),
            DB::raw('sum(expenses.amount - expenses_payments.value) as pending_amount'),
            'expense_description',
            'expense_date'
        )->groupby('expenses.id')->get();
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
