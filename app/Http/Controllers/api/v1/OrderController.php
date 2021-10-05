<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use App\Models\api\v1\Product;
use App\Models\api\v1\Order;
use App\Models\api\v1\OrderDetail;
use App\Http\Resources\api\v1\BranchResource;
use App\Http\Resources\api\v1\UserResource;
use App\Http\Resources\api\v1\OrderResouce;
use App\Http\Resources\api\v1\OrderDetailsResource;
use App\Http\Resources\api\v1\ReviewResource;

use Str;
use Carbon\Carbon;

class OrderController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        $orders = OrderResouce::collection($user->branch->orders->whereNotIn('status_id', [9, 10]));

        return response()->json($orders, 200);
    }

    public function create()
    {
        $return = [];
        $user = auth()->user();
        $branch = new BranchResource($user->branch);
        $categories = $user->branch->client->categories;
        $cateIds = $categories->pluck('id')->toArray();
        $products = Product::whereIn('category_id', $cateIds)->get();

        $due_date = $this->getDueDate($branch);

        $return['user'] = new UserResource($user);
        $return['branch'] = $branch;
        $return['products'] = $products;
        $return['categories'] = $categories;
        $return['due_date'] = date('Y-m-d', strtotime($due_date));

        return response()->json($return, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'due_date' => 'required',
            'items' => 'required'
        ]);

        // return $request->all();

        $user = auth()->user();

        $server_due = $this->getDueDate($user->branch);

        if(date('Y-m-d', strtotime($server_due)) > date('Y-m-d', strtotime($request->due_date))) {
            return response()->json(['message' => 'You cannot make order in this date ' . $request->due_date], 400);
        }

        if (Order::where('due_date', $request->due_date)
                ->where('branch_id', $user->branch_id)->exists()) {
            return response()->json(['message' => 'You cannot make order in this date ' . $request->due_date], 400);
        }

        $lastOrder = Order::latest()->first();

        DB::beginTransaction();
        $code = str_replace('-', '', $request->due_date);
        $code .= $user->branch_id;
        $code .= $lastOrder->id ?? 1;

        $order = Order::create([
            'code' => $code,
            'date' => date('Y-m-d'),
            'due_date' => $request->due_date,
            'branch_id' => $user->branch_id,
            'status_id' => $request->status,
            'created_id' => $user->id,
            'confirmed_id' => $request->status == 2 ? $user->id : null
        ]);
        
        if($order) {
            $data = [];
            foreach ($request->items as $item) {
                $data[] = [
                    'qty' => $item['qty'],
                    'order_id' => $order->id,
                    'product_id' => $item['id']
                ];
            }
            $details = OrderDetail::insert($data);
            if($details) {
                DB::commit();
                return response()->json(['message' => 'sucsses'], 200);
            } else {
                DB::rollBack();
            }
        } else {
            DB::rollBack();
        }
        return response()->json(['error' => 'Unknown Error'], 500);
    }

    public function show(Order $order)
    {        
        $products = OrderDetailsResource::collection($order->details);
        $order = new OrderResouce($order);

        return response()->json(['order' => $order, 'products' => $products], 200);
    }

    public function edit(Order $order)
    {
        $branch = new BranchResource($order->branch);
        $order_products = OrderDetailsResource::collection($order->details);
        $order = new OrderResouce($order);
        $products = $order->branch->client->categories()->with('products')->get();

        return response()->json([
            'branch' => $branch, 
            'order_products' => $order_products, 
            'order' => $order, 
            'products' => $products
        ], 200);
    }


    public function update(Order $order, Request $request)
    {
        $request->validate([
            'due_date' => 'required',
            'items' => 'required'
        ]);

        $user = auth()->user();

        if($request->due_date != $order->due_date) {

            $server_due = $this->getDueDate($user->branch);

            if($server_due > $request->due_date) {
                return response()->json(['message' => 'You cannot make order in this date ' . $request->due_date], 400);
            }

            if (Order::where('due_date', $request->due_date)
                    ->where('branch_id', $user->branch_id)->exists()) {
                return response()->json(['message' => 'You cannot make order in this date ' . $request->due_date], 400);
            }
        }

        DB::beginTransaction();

        $order->due_date = $request->due_date;
        $order->status_id = $request->status;
        $order->created_id = $user->id;
        $order->confirmed_id = $request->status == 2 ? $user->id : null;
        
        if($order->save()) {
            $data = [];
            foreach ($request->items as $item) {
                $data[] = [
                    'qty' => $item['qty'],
                    'order_id' => $order->id,
                    'product_id' => $item['id']
                ];
            }
            $order->details()->delete();
            $details = OrderDetail::insert($data);
            if($details) {
                DB::commit();
                return response()->json(['message' => 'sucsses'], 200);
            } else {
                DB::rollBack();
            }
        } else {
            DB::rollBack();
        }
        return response()->json(['error' => 'Unknown Error'], 500);
    }


    public function destroy(Order $order)
    {
        if($order) {
            if($order->delete())
                return response()->json(['message' => 'sucsses'], 200);
        } 

        return response()->json(['error' => 'Unknown Error'], 500);
    }


    public function getProductionOrders()
    {
        $orders = Order::with('branch')->where('status_id', 3)->get();

        $todayOrders = [];
        foreach ($orders as $order) {
            if(date('Y-m-d') == date('Y-m-d', strtotime($order->due_date .'-'. $order->branch->due_period .' day'))) {                
                //$production[] = ['order' => $order, 'products' => OrderDetailsResource::collection($order->details)];
                $todayOrders[] = $order;
            }
        }

        $production = Product::whereHas('productOrders', function (Builder $query) use ($todayOrders)
        {
            $query->whereIn('order_id', collect($todayOrders)->pluck('id')->toArray());
        })->with('productOrders', function ($query) use ($todayOrders)
        {
            $query->whereIn('order_id', collect($todayOrders)->pluck('id')->toArray());
        })->get();

        return ReviewResource::collection($production);
    }

    public function updateOrderStatusSchedule()
    {
        $orders = Order::with('branch')->where('status_id', 2)->get();
        foreach ($orders as $order) {
            
            if(date('Y-m-d') == date('Y-m-d', strtotime($order->due_date .'-'. $order->branch->due_period .' day'))) {
                if(date('H') >= $order->branch->close_time) {
                    $order->status_id = 3;
                    $order->save();
                }
            }
        }
    }

    public function updateApproved(Request $request)
    {
        $request->validate([
            'order' => 'required',
            'product' => 'required',
            'qty' => 'required'
        ]);

        $order = OrderDetail::where('order_id', $request->order)
                          ->where('product_id', $request->product)
                          ->update(['qty_approved' => $request->qty]);
        if($order) {
            return response()->json(['message' => 'sucsses'], 200);
        }

        return response()->json(['error' => 'Unknown Error'], 500);
    }


    public function getDueDate($branch, $production=false)
    {        
        if($branch->close_time <= date('H')) {
            $period_date = today()->addDays($branch->due_period + 1);
        } else {
            $period_date = today()->addDays($branch->due_period);
        }

        if(!$production) {
            $next_date = DB::select('SELECT MIN(X.due_date + INTERVAL 1 DAY) dt FROM orders X LEFT JOIN orders Y ON Y.due_date - INTERVAL 1 DAY = X.due_date AND Y.branch_id = X.branch_id WHERE Y.due_date IS NULL AND X.branch_id =' . $branch->id);
        }        

        return $next_date[0]->dt ?? $period_date;
    }
}
