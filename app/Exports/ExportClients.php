<?php

namespace App\Exports;

use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportClients implements FromCollection ,WithHeadings
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


        $clients = Client::query();

        if ($this->request->has('client_filter') and $this->request->get('client_filter') != "") {

            $clients->where('id',$this->request->get('client_filter'));
        }

        $clients->when(request('search') != null, function ($q) {
            return $q->where('name', 'like', '%' . request('search') . '%')->orWhere('phone', 'like', '%' . request('search') . '%');
        });


        if ($this->request->has('from') and $this->request->has('to') and $this->request->get('from') != "" and $this->request->get('to') != "") {
            $from=$this->request->get('from');
            $to=$this->request->get('to');

            $clients->whereBetween('created_at',[$from,$to]);
        }


        $clients->when($this->request->get('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        return $clients->select('name','phone','created_at')->get();

    }


    public function headings(): array
    {
        return ["الاسم","الهاتف","التاريخ"];
    }
}
