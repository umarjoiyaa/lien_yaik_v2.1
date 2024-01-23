<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Mail\OrderEmail;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class PurchaseOrderController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Purchase Order List') ||
            Auth::user()->hasPermissionTo('Purchase Order Create') ||
            Auth::user()->hasPermissionTo('Purchase Order Edit') ||
            Auth::user()->hasPermissionTo('Purchase Order Delete')
        ) {
            $purchases = PurchaseOrder::with('product','user')->get();
            Helper::logSystemActivity('Purchase Order', 'Purchase Order List');
            return view('productions.purchase-order.index', compact('purchases'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    
    public function create(){
        if (!Auth::user()->hasPermissionTo('Purchase Order Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $users = User::all();
        $products = Product::all();
        $materials = Material::all();
        Helper::logSystemActivity('Purchase Order', 'Purchase Order Create');
        return view('productions.purchase-order.create', compact('users', 'products', 'materials'));
    }

    public function store(Request $request){

        if (!Auth::user()->hasPermissionTo('Purchase Order Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'order_no' => [
                'required',
                Rule::unique('purchase_orders', 'order_no')->whereNull('deleted_at'),
            ],
            "customer_name" => "required",
            "product" => "required",
            "material" => "required",
            "order_date" => "required",
            "request_date" => "required",
            "order_unit" => "required",
            "cavities" => "required",
            "unit_kg" => "required",
            "weight" => "required",
            "issued_by" => "required",
            "approved_by" => "required"
        ]);

        $purchase = new PurchaseOrder();
        $purchase->customer = $request->customer_name;
        $purchase->product_id = $request->product;
        $purchase->item_id = json_encode($request->material);
        $purchase->order_no = $request->order_no;
        $purchase->order_date = $request->order_date;
        $purchase->req_date = $request->request_date;
        $purchase->order_unit = $request->order_unit;
        $purchase->cavities = $request->cavities;
        $purchase->unit_kg = $request->unit_kg;
        $purchase->per_mold = $request->weight;
        $purchase->issued = Auth::user()->id;
        $purchase->approved = json_encode($request->approved_by);
        $purchase->status = 0;
        $purchase->save();

        foreach($request->approved_by as $approved){
            $detail = new PurchaseOrderDetail();
            $detail->order_id = $purchase->id;
            $detail->user = $approved;
            $detail->save();

            $user = User::find($approved);
            $email = new OrderEmail($user, Auth::user()->name, $purchase);

            Mail::to($user->email)->send($email);
        }

        Helper::logSystemActivity('Purchase Order', 'Purchase Order Create');
        return redirect()->route('purchase.index')->with('custom_success', 'Purchase Order has been Succesfully Created!');
    }

    public function edit($id){

        if (!Auth::user()->hasPermissionTo('Purchase Order Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $purchase = PurchaseOrder::find($id);
        $users = User::all();
        $products = Product::all();
        $materials = Material::all();
        Helper::logSystemActivity('Purchase Order', 'Purchase Order Edit');
        return view('productions.purchase-order.edit', compact('purchase', 'users', 'products', 'materials'));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('Purchase Order Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'order_no' => [
                'required',
                Rule::unique('purchase_orders', 'order_no')->whereNull('deleted_at')->ignore($id),
            ],
            "customer_name" => "required",
            "product" => "required",
            "material" => "required",
            "order_date" => "required",
            "request_date" => "required",
            "order_unit" => "required",
            "cavities" => "required",
            "unit_kg" => "required",
            "weight" => "required",
            "issued_by" => "required",
            "approved_by" => "required"
        ]);

        $purchase = PurchaseOrder::find($id);
        $purchase->customer = $request->customer_name;
        $purchase->product_id = $request->product;
        $purchase->item_id = json_encode($request->material);
        $purchase->order_no = $request->order_no;
        $purchase->order_date = $request->order_date;
        $purchase->req_date = $request->request_date;
        $purchase->order_unit = $request->order_unit;
        $purchase->cavities = $request->cavities;
        $purchase->unit_kg = $request->unit_kg;
        $purchase->per_mold = $request->weight;
        $purchase->issued = Auth::user()->id;
        $purchase->approved = json_encode($request->approved_by);
        $purchase->status = 0;
        $purchase->save();

        PurchaseOrderDetail::where('order_id', '=', $request->id)->delete();

        foreach($request->approved_by as $approved){
            $detail = new PurchaseOrderDetail();
            $detail->order_id = $purchase->id;
            $detail->user = $approved;
            $detail->save();

            $user = User::find($approved);
            $email = new OrderEmail($user, Auth::user()->name, $purchase);

            Mail::to($user->email)->send($email);
        }

        Helper::logSystemActivity('Purchase Order', 'Purchase Order Update');
        return redirect()->route('purchase.index')->with('custom_success', 'Purchase Order has been Succesfully Updated!');        
    }

    public function  destroy($id){

        if (!Auth::user()->hasPermissionTo('Purchase Order Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $purchase = PurchaseOrder::find($id);
        $Production = ProductionOrder::where('order_id', '=', $id)->first();
        if($Production){
            return back()->with('custom_errors', 'This PURCHASE ORDER is used in PRODUCTION ORDER!');
        }
        $purchase->delete();
        Helper::logSystemActivity('Purchase Order', 'Purchase Order Delete');
        return redirect()->route('purchase.index')->with('custom_success', 'Purchase Order has been Succesfully Deleted!');
    }

    public function pdf($id)
    {
        $data = PurchaseOrder::find($id);
        $decoded_user = json_decode($data->approved);
        $users = User::whereIn('id',$decoded_user)->get();
        $userNames = $users->pluck('name')->implode(', ');
        $pdf = PDF::loadView('productions.purchase-order.pdf', [
            'data' => $data,
            'approved' => $userNames
        ]);
        return $pdf->stream('productions.purchase-order.pdf');
    }
}
