<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\StakeHolder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::query();
        $orders = $orders->with('customer', 'orderitems', 'orderitems.product');
        $per_page = 10;
        $orders->when(request('order_status') != null, function ($q) {
            return $q->where('order_status', request('order_status'));
        });

        $orders->when(request('search') != null, function ($q) {
            return $q->where('order_number', 'like', '%' . request('search') . '%')->orWhereHas('customer', function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
        });

        $orders->when(request('filter') == 'sort_asc', function ($q) {
            return $q->orderBy('created_at', 'asc');
        },function ($q) {
            return $q->orderBy('created_at', 'desc');
        });

        if ($request->has('rows')):
            $per_page = $request->query('rows');
        endif;

        $orders = $orders->paginate($per_page);
        return view(config('app.theme').'.pages.order.index', compact('orders'));

    }
    public function ajax_get_customer_info($id)
    {
        $data = StakeHolder::where('id', $id)->first();
        return response()->json($data);
    }
    public function ajax_get_product_info($id)
    {
        $product = Product::with('stock','stock.supplier')->where('id',$id)->first();
        return response()->json(['product' => $product]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = StakeHolder::select('id','name')->get();
        $products  = Product::whereHas('stock')->get();
        return view(config('app.theme').'.pages.order.create', compact('customers', 'products'));
    }

    /**
     * Show the form for editing a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order     = Order::with('orderitems')->where('id',$id)->first();
        $customers = StakeHolder::select('id','name')->get();
        $products  = Product::all();
        return view(config('app.theme').'.pages.order.edit', compact('order','customers','products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // $stock=array();
        // foreach ($request->addmore as $item) {
        //     $stock[] = Product::where('id', $item->product_id)->value('quantity');
        // }
        $request->validate([
            'order_number'         => ['required','unique:orders,order_number'],
            'addmore.*.product_id' => 'required',
            'addmore.*.qty'        => ['required', 'numeric'],
            'addmore.*.price'      => ['required', 'numeric']
        ]);

        $order = Order::Create([
            'order_number' => $request->input('order_number'),
            'customer_id'  => $request->input('customer_id'),
            'total_price'  => 0,
            'quantity'     => count($request->input('addmore')),
            'order_status' => 'pending',
            'discount'     => $request->input('discount'),
        ]);

        array_filter($request->input('addmore'));

        foreach($request->input('addmore') as $value):
            $product = Product::with('stock')->where('id', $value['product_id'])->first();
            if(!isset($value['price'])):
                $value['price'] =  $product->stock->price;
            endif;

            if(!isset($value['qty'])):
                $value['qty'] =  1;
            endif;

            if($value['qty'] > $product->stock->quantity):
                return redirect()->back()->withErrors([
                    'qty' => ['كمية المنتج ' . $product->name . 'اكبر من المخزون']
                ]);
            else:
                $order->orderitems()->create($value);
            endif;
        endforeach;

        if($order->orderitems()->count() == count($request->input('addmore'))):
            $order->update([
                'total_price' => $order->orderitems()->sum(DB::raw("(order_items.qty * order_items.price)")) - $order->discount,
                'quantity' => $order->orderitems()->count()
            ]);
        else:
            $order->delete();
        endif;
        return redirect()->route('admin.orders.index')->with('success_message', 'تم اضافة طلب');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // $stock=array();
        // foreach ($request->addmore as $item) {
        //     $stock[] = Product::where('id', $item->product_id)->value('quantity');
        // }
        $request->validate([
            'order_number'         => ['required','unique:orders,order_number,'.$id],
            'addmore.*.product_id' => 'required',
            'addmore.*.qty'        => ['required', 'numeric'],
            'addmore.*.price'      => ['required', 'numeric']
        ]);

        $order = Order::where([
            'id' => $id
        ])->first();

        $order->update([
            'order_number' => $request->input('order_number'),
            'customer_id'  => $request->input('customer_id'),
            'total_price'  => 0,
            'quantity'     => count($request->input('addmore')),
            'order_status' => 'pending',
            'discount'     => $request->input('discount'),
        ]);

        array_filter($request->input('addmore'));

        $order->orderItems()->delete();

        foreach($request->input('addmore') as $value):
            $product = Product::with('stock')->where('id', $value['product_id'])->first();
            if(!isset($value['price'])):
                $value['price'] =  $product->stock->sale_price;
            endif;

            if(!isset($value['qty'])):
                $value['qty'] =  1;
            endif;

            if($value['qty'] > $product->stock->quantity):
                return redirect()->back()->withErrors([
                    'qty' => ['كمية المنتج ' . $product->name . 'اكبر من المخزون']
                ]);
            else:
                $order->orderitems()->create($value);
            endif;
        endforeach;

        if($order->orderitems()->count() == count($request->input('addmore'))):
            $order->update([
                'sub_total'   => $order->orderitems()->sum(DB::raw("(order_items.qty * order_items.price)")),
                'total_price' => $order->orderitems()->sum(DB::raw("(order_items.qty * order_items.price)")) - $order->discount,
                'quantity'    => $order->orderitems()->count()
            ]);
        else:
            $order->delete();
        endif;
        return redirect()->back()->with('success_message', 'تم اضافة طلب');

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        $order->delete();
        $order->orderItems()->delete();
        return redirect()->route('admin.orders.index');

    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order     = Order::with('orderitems','orderitems.product','customer')->where('id',$id)->first();
        return view(config('app.theme').'.pages.order.show', compact('order'));
    }
}
