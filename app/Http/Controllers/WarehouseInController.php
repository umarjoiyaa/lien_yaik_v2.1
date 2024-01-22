<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Pellete;
use App\Models\ProductionOrder;
use App\Models\WarehouseIn;
use App\Models\WarehouseInDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseInController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('WareHouse In List') ||
            Auth::user()->hasPermissionTo('WareHouse In Create') ||
            Auth::user()->hasPermissionTo('WareHouse In Edit') ||
            Auth::user()->hasPermissionTo('WareHouse In Delete')
        ) {
            $warehouse_ins = WarehouseIn::all();
            Helper::logSystemActivity('WareHouse In', 'WareHouse In List');
            return view('warehouses.warehouse-in.index', compact('warehouse_ins'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('WareHouse In Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $batchIdsWithoutWarehouseIn = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) {
                $query->select('warehouse_ins.batch_id')->from('warehouse_ins')
                ->whereNull('warehouse_ins.deleted_at');
            })
            ->pluck('production_orders.batch_id');
        $batches = Batch::whereIn('id', $batchIdsWithoutWarehouseIn)->get();
        Helper::logSystemActivity('WareHouse In', 'WareHouse In Create');
        return view('warehouses.warehouse-in.create', compact('batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('WareHouse In Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $warehouse_in = new WarehouseIn();
        $warehouse_in->date = $request->date;
        $warehouse_in->remarks = $request->remarks;
        $warehouse_in->batch_id = $request->batch_no;
        $warehouse_in->operator_id = Auth::user()->id;
        $warehouse_in->save();

        $total_pcs = 0;
        
        foreach ($request->pelletes as $value) {
            $warehouse_in_details = new WarehouseInDetail();
            $warehouse_in_details->pellete_id = $value['id'];
            $warehouse_in_details->wi_id = $warehouse_in->id;
            $warehouse_in_details->weight = $value['weight'] ?? 0;
            $warehouse_in_details->pcs = $value['pcs'] ?? 0;
            $warehouse_in_details->save();

            $product = ProductionOrder::distinct('product_id')
            ->join('warehouse_ins', 'warehouse_ins.batch_id', '=', 'production_orders.batch_id')
            ->where('warehouse_ins.id', $warehouse_in->id)
            ->pluck('product_id');

            $pellete = Inventory::where('pellete_id', $value['id'])->first();
            $pellete->value = (float)$pellete->value + (float)$value["pcs"];
            $pellete->weight = (float)$pellete->weight + (float)$value["weight"];
            $pellete->product_id = $product[0];
            $pellete->save();
            
            $actual = Pellete::find($value['id']);
            $actual->previous_batch = $actual->batch;
            $actual->previous_weight = $actual->weight;
            $actual->previous_pcs = $actual->pcs;
            $actual->batch = $request->batch_no;
            $actual->weight = $pellete->weight;
            $actual->pcs = $pellete->value;
            $actual->status = 'Warehouse In';
            $actual->save();

            $total_pcs += $value['pcs'];
        }

        $warehouse_in_update = WarehouseIn::find($warehouse_in->id);
        $warehouse_in_update->total_pcs = $total_pcs;
        $warehouse_in_update->save();

        Helper::logSystemActivity('WareHouse In', 'WareHouse In Store');
        return redirect()->route('warehouse-in.index')->with('custom_success', 'WareHouse In has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('WareHouse In Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $batchIdsWithoutWarehouseIn = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) use ($id) {
                $query->select('warehouse_ins.batch_id')->from('warehouse_ins')
                ->whereNull('warehouse_ins.deleted_at')
                ->where('warehouse_ins.batch_id', '!=', $id);
            })
            ->pluck('production_orders.batch_id');
        $batches = Batch::whereIn('id', $batchIdsWithoutWarehouseIn)->get();

        $warehouse_in = WarehouseIn::find($id);
        $details = WarehouseInDetail::where('wi_id', '=', $id)->with('pellete')->get();

        Helper::logSystemActivity('WareHouse In', 'WareHouse In Edit');
        return view('warehouses.warehouse-in.edit', compact('batches', 'details', 'warehouse_in'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('WareHouse In Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $warehouse_in = WarehouseIn::find($id);
        $warehouse_in->date = $request->date;
        $warehouse_in->remarks = $request->remarks;
        $warehouse_in->batch_id = $request->batch_no;
        $warehouse_in->operator_id = Auth::user()->id;
        $warehouse_in->save();

        $total_pcs = 0;
        
        $delete = WarehouseInDetail::where('wi_id', '=', $id);

        foreach ($request->pelletes as $value) {
            $warehouse_in_details = new WarehouseInDetail();
            $warehouse_in_details->pellete_id = $value['id'];
            $warehouse_in_details->wi_id = $warehouse_in->id;
            $warehouse_in_details->weight = $value['weight'] ?? 0;
            $warehouse_in_details->pcs = $value['pcs'] ?? 0;
            $warehouse_in_details->save();

            $product = ProductionOrder::distinct('product_id')
            ->join('warehouse_ins', 'warehouse_ins.batch_id', '=', 'production_orders.batch_id')
            ->where('warehouse_ins.id', $warehouse_in->id)
            ->pluck('product_id');

            $deduct_qty = WarehouseInDetail::where('wi_id', '=', $id)->where('product_id', '=', $product[0]->id)->first();

            $pellete = Inventory::where('pellete_id', $value['id'])->first();
            $pellete->value = (float)$pellete->value + ((float)$value["pcs"] - (float)$deduct_qty->pcs);
            $pellete->weight = (float)$pellete->weight + ((float)$value["weight"] - (float)$deduct_qty->weight);
            $pellete->product_id = $product[0];
            $pellete->save();
            
            $actual = Pellete::find($value['id']);
            $actual->previous_batch = $actual->batch;
            $actual->previous_weight = $actual->weight;
            $actual->previous_pcs = $actual->pcs;
            $actual->batch = $request->batch_no;
            $actual->weight = $pellete->weight;
            $actual->pcs = $pellete->value;
            $actual->status = 'Warehouse In';
            $actual->save();

            $total_pcs += $value['pcs'];
        }

        $delete->delete();

        $warehouse_in_update = WarehouseIn::find($warehouse_in->id);
        $warehouse_in_update->total_pcs = $total_pcs;
        $warehouse_in_update->save();

        Helper::logSystemActivity('WareHouse In', 'WareHouse In Update');
        return redirect()->route('warehouse-in.index')->with('custom_success', 'WareHouse In has been Succesfully Updated!');
    }

    public function destroy($id){
        $warehouse_in = WarehouseIn::find($id);
        $details = WarehouseInDetail::where('wi_id', '=', $id)->get();
        foreach ($details as $value) {
            $deduct_qty = WarehouseInDetail::where('wi_id', '=', $id)->where('pellete_id', '=', $value->pellete_id)->first();
            $pellete = Inventory::where('pellete_id', '=', $value->pellete_id)->first();
            $pellete->value = (float)$pellete->value - (float)$deduct_qty->pcs;
            $pellete->weight = (float)$pellete->weight - (float)$deduct_qty->weight;
            $pellete->save();
        }
        WarehouseInDetail::where('wi_id', '=', $id)->delete();
        $warehouse_in->delete();
        Helper::logSystemActivity('WareHouse In', 'WareHouse In Delete');
        return redirect()->route('warehouse-in.index')->with('custom_success', 'WareHouse In has been Succesfully Deleted!');
    }

    public function batches(Request $request)
    {
        $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
        $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');
        return response()->json(['production' => $production, 'weight_unit' => $weight_unit]);
    }

    public function pelletes(Request $request)
    {
        $search = Pellete::where('pellete_no', '=', $request->search)->first();
        if(!empty($search)){
            return response()->json(['pellete' => $search]);
        }else{
            return response()->json('Not Get');
        }
    }

    public function scan($id)
    {
        $scan = Pellete::select('pelletes.pellete_no', 'warehouse_in_details.pellete_id')
        ->join('warehouse_in_details', 'pelletes.id', '=', 'warehouse_in_details.pellete_id')
        ->where('warehouse_in_details.wi_id', $id)
        ->get();
        return view('warehouses.warehouse-in.scan', compact('scan'));
    }
}
