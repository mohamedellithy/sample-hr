<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportShift implements FromCollection,WithMapping ,WithHeadings
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
        $request = $this->request;

        $shifts = Shift::query();

        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $shifts->whereBetween('date',[$from,$to]);
        }

        if ($request->has('employee_filter') and $request->get('employee_filter') != "") {
            $shifts->where('employee_id',$request->get('employee_filter'));
        }

        if ($request->has('in') and $request->get('in') != "") {
            $shifts->where('clock_in', 'like', '%' .$request->get('in') . '%');
        }
        if ($request->has('out') and $request->get('out') != "") {
            $shifts->where('clock_out', 'like', '%' .$request->get('out') . '%');
        }


        $shifts->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('date', 'asc');
        },function ($q) {
            return $q->orderBy('date', 'desc');
        });

        return $shifts->get();

    }


    public function map($shifts): array
    {
        return [
            $shifts->employee->name,
            $shifts->date,
            $shifts->clock_in,
            $shifts->clock_out,

        ];
    }


    public function headings(): array
    {
        return ["الاسم","التاريخ","الحضور","الانصراف"];
    }


}
