<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoneyResource;
use App\Models\Sale;
use Carbon\Carbon;
use App\Exports\ExportMoneyResources;
use Maatwebsite\Excel\Facades\Excel;
class MoneyResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $resources       = MoneyResource::query();
        $per_page = 10;


        if ($request->has('datefilter') and $request->get('datefilter') != "") {
            $result = explode('-',$request->get('datefilter'));
            $from = Carbon::parse($result[0])->format('Y-m-d');
            $to= Carbon::parse($result[1])->format('Y-m-d');
            $resources->whereBetween('resource_date',[$from,$to]);
        }


        $resources->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('resource_date', 'asc');
        },function ($q) {
            return $q->orderBy('resource_date', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $resources = $resources->select('id','value','type','resource_date')->paginate($per_page);
        return view('pages.moneyResources.index',compact('resources'));
    }

    public function money_resources_export(Request $request){
        return Excel::download(new ExportMoneyResources($request->search,$request->datefilter,$request->filter),'money-resources.xlsx');

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
        //
        MoneyResource::create([
            'value' => $request->input('value'),
            'type' => $request->input('type'),
            'resource_date' => $request->input('resource_date')
        ]);

        flash('تم اضافة المورد بنجاح', 'success');
        return back();
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
        $money_resource   = MoneyResource::find($id);
        return response()->json([
            'status' => true,
            'view'   => view('pages.moneyResources.model.edit', compact('money_resource'))->render()
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
        //
        MoneyResource::where([
            'id'  => $id
        ])->update([
            'value'         => $request->input('value'),
            'type'          => $request->input('type'),
            'resource_date' => $request->input('resource_date')
        ]);

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
        //
        MoneyResource::where([
            'id'  => $id
        ])->delete();

        flash('تم الحذف بنجاح', 'warning');
        return back();
    }
}
