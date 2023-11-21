<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\ClientPayment;

class ExportClientPayments implements FromCollection ,WithHeadings
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
        $client_payments = ClientPayment::query();

    
        $client_payments->when($this->filter  == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });


        if ($this->datefilter and $this->datefilter != "") {
            $result = explode('-',$this->datefilter);
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $client_payments->whereBetween('created_at',[$from,$to]);
        }

        $client_payments->Join('clients','clients.id','=','client_payments.client_id');

        return $client_payments->select(
            'client_payments.id',
            'clients.name as client_name',
            'client_payments.amount',
            'client_payments.created_at'
        )->groupby('client_payments.id','clients.name')->get();
    }

    public function headings(): array
    {
        return [
            'id',
            'client_name',
            'amount',
            'date'
        ];
    }
}
