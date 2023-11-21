<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use App\Models\ClientSale;
use Illuminate\Http\Request;
use App\Exports\ExportClientSales;
use App\Exports\ExportClientPayments;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\ClientPayment;

class ClientsSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::get();
        $clientSales = Client::query();
        $per_page = 10;


        if ($request->has('client_filter') and $request->get('client_filter') != "") {
            $clientSales->where('id',$request->get('client_filter'));
        }

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $clientSales = $clientSales->Join('client_sales','clients.id','=','client_sales.client_id')
        ->select(
            DB::raw('sum(amount)   as total_amount'),
            'clients.name','clients.id'
        )->groupBy('clients.name','clients.id')->paginate($per_page);

        return view('pages.clientSales.index', compact('clientSales','clients'));
    }

    public function exportClientSales(Request $request){


        return Excel::download(new ExportClientSales( $request),'ClientSales.xlsx');

         return redirect()->back();

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

        ClientPayment::create([
            'client_id' => $request->input('client_id'),
            'amount'    => $request->input('paid')
        ]);

        flash('تم الاضافه بنجاح', 'success');
        return redirect()->back();
       }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //
        $clientSales = ClientSale::query();
        $clientSales = ClientSale::where('client_id',$id);
        $per_page = 10;


        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
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

        $clientSales   = $clientSales->paginate($per_page);
        $client        = Client::find($id);
        $ClientSale    = ClientSale::where('client_id',$id)->get();
        return view('pages.clientSales.show', compact('clientSales','client','ClientSale'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_client_payments(Request $request,$client_id)
    {
        //
        $clientPayments = ClientPayment::query();
        $clientPayments = ClientPayment::where('client_id',$client_id);
        $per_page = 10;
        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $clientPayments->whereBetween('created_at',[$from,$to]);
        }

        if ($request->has('client_filter') and $request->get('client_filter') != "") {

            $clientPayments->where('client_id',$request->get('client_filter'));
        }


        $clientPayments->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $clientPayments   = $clientPayments->paginate($per_page);
        $client        = Client::find($client_id);
        $ClientSale    = ClientSale::where('client_id',$client_id)->get();
        return view('pages.clientSales.payments', compact('clientPayments','client','ClientSale'));
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

    public function edit_client_payments($payment_id){
        $clientPayment   = ClientPayment::where('id',$payment_id)->first();
        return response()->json([
            'status' => true,
            'view'   => view('pages.clientSales.model.payment', compact('clientPayment'))->render()
        ]);
    }

    public function update_client_payments(Request $request,$payment_id){
        ClientPayment::where('id',$payment_id)->update([
            'amount' => $request->input('amount')
        ]);

        flash('تم تحديث بنجاح', 'success');
        return back();
    }

    public function destroy_client_payments($payment_id){
        ClientPayment::where('id',$payment_id)->delete();
        flash('تم الحذف بنجاح', 'success');
        return back();
    }
//update_client_payments

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
            'amount'    => ['required','numeric'],
            'sale_date' => ['required','date']
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric'  => 'يرجى ادخال رقم',
            'date'     => 'يجب ادخال تاريخ',
        ]);

        ClientSale::where('id', $id)->update($request->only([
            'client_id',
            'amount',
            'sale_date',
        ]));

        flash('تم التعديل بنجاح', 'warning');
        return redirect()->back();
    }

    public function client_payemnts(Request $request){
        $request->validate([
            'client_id' => 'required',
            'amount'    => ['required','numeric'],
        ],[
            'required' => 'هذا الحقل مطلوب',
            'numeric'  => 'يرجى ادخال رقم',
            'date'     => 'يجب ادخال تاريخ',
        ]);

        $client_id = $request->input('client_id');
        ClientPayment::create([
            'client_id' => $client_id,
            'amount'    => $request->input('amount')
        ]);

        return back();
    }

    public function exportClientPayments(Request $request){
        return Excel::download(new ExportClientPayments($request->search,$request->datefilter,$request->filter),'clients-payments.xlsx');

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
