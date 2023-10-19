<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportEmployee implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $search;
    private $filter;

    public function __construct($search,$filter)
    {
        $this->search = $search;
        $this->filter = $filter;
    }

    public function collection()
    {

        $employees = Employee::query();

        $employees->when($this->search  != null, function ($q) {
            return $q->where('nationality', 'like', '%' .  $this->search  . '%')
            ->orWhere('name', 'like', '%' .  $this->search );

        });

        $employees->when( $this->filter == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        return $employees->get();
    }
}
