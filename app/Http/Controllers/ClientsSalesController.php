<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientSale;
use Illuminate\Http\Request;
use App\Exports\ExportClientSales;
use Maatwebsite\Excel\Facades\Excel;


class ClientsSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientSales = ClientSale::query();
        $per_page = 10;


        if ($request->has('from') and $request->has('to') and $request->get('from') != "" and $request->get('to') != "") {
            $from=$request->get('from');
            $to=$request->get('to');

            $clientSales->whereBetween('sale_date',[$from,$to]);
        }

        if ($request->has('client_filter') and $request->get('client_filter') != "") {

            $clientSales->where('client_id',$request->get('client_filter'));
        }


        $clientSales->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('sale_date', 'asc');
        },function ($q) {
            return $q->orderBy('sale_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $clientSales = $clientSales->paginate($per_page);
        $clients = Client::get();
        return view('pages.clientSales.index', compact('clientSales','clients'));
    }

    public function exportClientSales(Request $request){


        return Excel::download(new ExportClientSales( $request),'ClientSales.xlsx');

         return redirect()->back();

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
    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required',
            'amount' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        ClientSale::create($request->only([
            'client_id',
            'amount',
            'sale_date',
        ]));
        flash('تم الاضافه بنجاح', 'success');
        return redirect()->back();
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
        $clientSale   = ClientSale::find($id);
        $clients = Client::get();

        return response()->json([
            'status' => true,
            'view'   => view('pages.clientSales.model.edit', compact('clientSale','clients'))->render()
        ]);
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
        $request->validate([
            'client_id' => 'required',
            'amount' => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric' => 'يرجى ادخال رقم',
            'date' => 'يجب ادخال تاريخ',
        ]);

        ClientSale::where('id', $id)->update($request->only([
            'client_id',
            'amount',
            'sale_date',
        ]));
        flash('تم التعديل بنجاح', 'warning');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClientSale::find($id);
        ClientSale::destroy($id);
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
