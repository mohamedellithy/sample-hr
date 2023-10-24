<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployee implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {

        $employees = Employee::query();

        $employees->when($this->request->search  != null, function ($q) {
            return $q->where('nationality', 'like', '%' .  $this->request->search  . '%')
            ->orWhere('name', 'like', '%' .  $this->request->search );

        });

        $employees->when( $this->request->filter == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        return $employees->get();
    }



    public function headings(): array
    {
        return ["#","الاسم","الجنسيه","المرتب","رقم الباسبور","تاريخ الميلاد","تاريخ انتهاء الباسبور","تاريخ الانظمام"];
    }

}
