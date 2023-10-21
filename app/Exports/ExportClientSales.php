<?php

namespace App\Exports;

use App\Models\ClientSale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportClientSales implements FromCollection,WithMapping ,WithHeadings
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $clientSales = ClientSale::query();
        $clientSales = $clientSales->with('client');
        if ($this->request->has('from') and $this->request->has('to') and $this->request->get('from') != "" and $this->request->get('to') != "") {
            $from=$this->request->get('from');
            $to=$this->request->get('to');

            $clientSales->whereBetween('sale_date',[$from,$to]);
        }

        if ($this->request->has('client_filter') and $this->request->get('client_filter') != "") {

            $clientSales->where('client_id',$this->request->get('client_filter'));
        }


        $clientSales->when($this->request->filter == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

        return $clientSales->get();

    }

    public function map($clientSales): array
    {
        return [
            $clientSales->client->name,
            $clientSales->amount,
            $clientSales->sale_date,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","المبلغ","التاريخ"];
    }
}
