<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = Sale::query();
        $per_page = 10;


        if ($request->has('from') and $request->has('to') and $request->get('from') != "" and $request->get('to') != "") {
            $from=$request->get('from');
            $to=$request->get('to');

            $sales->whereBetween('sale_date',[$from,$to]);
        }


        $sales->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $sales = $sales->paginate($per_page);

        return view('pages.sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleRequest $request)
    {
        Sale::create($request->only([
            'cash',
            'bank',
            'discount',
            'credit_sales',
            'sale_date',

        ]));
        return redirect()->back()->with('success_message', 'تم اضافة المبايعه');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
