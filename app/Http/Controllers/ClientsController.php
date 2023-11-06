<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Exports\ExportClients;
use Maatwebsite\Excel\Facades\Excel;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = Client::query();
        $per_page = 10;

        if ($request->has('client_filter') and $request->get('client_filter') != "") {

            $clients->where('id',$request->get('client_filter'));
        }

        $clients->when(request('search') != null, function ($q) {
            return $q->where('name', 'like', '%' . request('search') . '%')->orWhere('phone', 'like', '%' . request('search') . '%');
        });


        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $clients->whereBetween('created_at',[$from,$to]);
        }


        $clients->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $clients = $clients->paginate($per_page);
        $filterclients = Client::get();
        return view('pages.clients.index', compact('clients','filterclients'));
    }

    public function exportClients(Request $request){
       return Excel::download(new ExportClients( $request),'clients.xlsx');
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
            'name' => 'required',
        ],[
            'required' => 'هذا الحقل مطلوب',
        ]);

        Client::create($request->only([
            'name',
            'phone',
        ]));
        flash('تم اضافه العميل بنجاح', 'success');
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
        $client   = Client::find($id);
        return response()->json([
            'status' => true,
            'view'   => view('pages.clients.model.edit', compact('client'))->render()
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
         Client::where('id', $id)->update($request->only([
            'name',
            'phone',
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
        Client::find($id);
        Client::destroy($id);
        flash('تم الحذف بنجاح', 'error');
        return redirect()->back();
    }
}
