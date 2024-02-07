<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Inventory;
use App\Models\Pellete;
use App\Models\ProductionOrder;
use App\Models\WarehouseOut;
use App\Models\WarehouseOutDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseOutController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('WareHouse Out List') ||
            Auth::user()->hasPermissionTo('WareHouse Out Create') ||
            Auth::user()->hasPermissionTo('WareHouse Out Edit') ||
            Auth::user()->hasPermissionTo('WareHouse Out Delete')
        ) {
            $warehouse_outs = WarehouseOut::all();
            Helper::logSystemActivity('WareHouse Out', 'WareHouse Out List');
            return view('warehouses.warehouse-out.index', compact('warehouse_outs'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('WareHouse Out Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $batches = Batch::all();
        Helper::logSystemActivity('WareHouse Out', 'WareHouse Out Create');
        return view('warehouses.warehouse-out.create', compact('batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('WareHouse Out Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $warehouse_out = new WarehouseOut();
        $warehouse_out->date = $request->date;
        $warehouse_out->remarks = $request->remarks;
        $warehouse_out->batch_id = $request->batch_no;
        $warehouse_out->operator_id = Auth::user()->id;
        $warehouse_out->save();

        $total_pcs = 0;
        
        foreach ($request->pelletes as $value) {
            $warehouse_out_details = new WarehouseOutDetail();
            $warehouse_out_details->pellete_id = $value['id'];
            $warehouse_out_details->wo_id = $warehouse_out->id;
            $warehouse_out_details->weight = $value['weight'] ?? 0;
            $warehouse_out_details->pcs = $value['pcs'] ?? 0;
            $warehouse_out_details->save();

            $product = ProductionOrder::distinct('product_id')
            ->join('warehouse_outs', 'warehouse_outs.batch_id', '=', 'production_orders.batch_id')
            ->where('warehouse_outs.id', $warehouse_out->id)
            ->pluck('product_id');

            $pallet = Inventory::where('pellete_id', $value['id'])->first();
            $pallet->value = ((float)$pallet->value - (float)$value["pcs"] < 0) ? 0 : (float)$pallet->value - (float)$value["pcs"];
            $pallet->weight = ((float)$pallet->weight - (float)$value["weight"] < 0) ? 0 : (float)$pallet->weight - (float)$value["weight"];
            $pallet->product_id = $product[0];
            $pallet->save();
            
            $actual = Pellete::find($value['id']);
            $actual->previous_batch = $actual->batch;
            $actual->previous_weight = $actual->weight;
            $actual->previous_pcs = $actual->pcs;
            $actual->batch = $request->batch_no;
            $actual->weight = $pallet->weight;
            $actual->pcs = $pallet->value;
            $actual->status = 'WareHouse Out';
            $actual->save();

            $total_pcs += $value['pcs'];
        }

        $warehouse_out_update = WarehouseOut::find($warehouse_out->id);
        $warehouse_out_update->total_pcs = $total_pcs;
        $warehouse_out_update->save();

        Helper::logSystemActivity('WareHouse Out', 'WareHouse Out Store');
        return redirect()->route('warehouse-out.index')->with('custom_success', 'WareHouse Out has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('WareHouse Out Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $batches = Batch::all();

        $warehouse_out = WarehouseOut::find($id);
        $details = WarehouseOutDetail::where('wo_id', '=', $id)->with('pellete')->get();

        Helper::logSystemActivity('WareHouse Out', 'WareHouse Out Edit');
        return view('warehouses.warehouse-out.edit', compact('batches', 'details', 'warehouse_out'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('WareHouse Out Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $warehouse_out = WarehouseOut::find($id);
        $warehouse_out->date = $request->date;
        $warehouse_out->remarks = $request->remarks;
        $warehouse_out->batch_id = $request->batch_no;
        $warehouse_out->operator_id = Auth::user()->id;
        $warehouse_out->save();

        $total_pcs = 0;
        
        foreach ($request->pelletes as $value) {            
            $product = ProductionOrder::distinct('product_id')
            ->join('warehouse_outs', 'warehouse_outs.batch_id', '=', 'production_orders.batch_id')
            ->where('warehouse_outs.id', $warehouse_out->id)
            ->pluck('product_id');

            $deduct_qty = WarehouseOutDetail::where('wo_id', '=', $id)->where('product_id', '=', $product[0])->first();

            $pallet = Inventory::where('pellete_id', $value['id'])->first();
            $pallet->value = ((float)$pallet->value - ((float)$value["pcs"] + (float)$deduct_qty->pcs) < 0) ? 0 : (float)$pallet->value - ((float)$value["pcs"] + (float)$deduct_qty->pcs);
            $pallet->weight = ((float)$pallet->weight - ((float)$value["weight"] + (float)$deduct_qty->weight) < 0) ? 0 : (float)$pallet->weight - ((float)$value["weight"] + (float)$deduct_qty->weight);
            $pallet->product_id = $product[0];
            $pallet->save();
            
            $deduct_qty->delete();

            $warehouse_out_details = new WarehouseOutDetail();
            $warehouse_out_details->pellete_id = $value['id'];
            $warehouse_out_details->wo_id = $warehouse_out->id;
            $warehouse_out_details->weight = $value['weight'] ?? 0;
            $warehouse_out_details->pcs = $value['pcs'] ?? 0;
            $warehouse_out_details->save();

            $actual = Pellete::find($value['id']);
            $actual->previous_batch = $actual->batch;
            $actual->previous_weight = $actual->weight;
            $actual->previous_pcs = $actual->pcs;
            $actual->batch = $request->batch_no;
            $actual->weight = $pallet->weight;
            $actual->pcs = $pallet->value;
            $actual->status = 'WareHouse Out';
            $actual->save();

            $total_pcs += $value['pcs'];
        }

        $warehouse_out_update = WarehouseOut::find($warehouse_out->id);
        $warehouse_out_update->total_pcs = $total_pcs;
        $warehouse_out_update->save();

        Helper::logSystemActivity('WareHouse Out', 'WareHouse Out Update');
        return redirect()->route('warehouse-out.index')->with('custom_success', 'WareHouse Out has been Succesfully Updated!');
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('WareHouse Out Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $warehouse_out = WarehouseOut::find($id);
        $details = WarehouseOutDetail::where('wo_id', '=', $id)->get();
        foreach ($details as $value) {
            $deduct_qty = WarehouseOutDetail::where('wo_id', '=', $id)->where('pellete_id', '=', $value->pellete_id)->first();
            $pellete = Inventory::where('pellete_id', '=', $value->pellete_id)->first();
            $pellete->value = ((float)$pellete->value + (float)$deduct_qty->pcs < 0) ? 0 : (float)$pellete->value + (float)$deduct_qty->pcs;
            $pellete->weight = ((float)$pellete->weight + (float)$deduct_qty->weight < 0) ? 0 : (float)$pellete->weight + (float)$deduct_qty->weight;
            $pellete->save();
        }
        WarehouseOutDetail::where('wo_id', '=', $id)->delete();
        $warehouse_out->delete();
        Helper::logSystemActivity('WareHouse Out', 'WareHouse Out Delete');
        return redirect()->route('warehouse-out.index')->with('custom_success', 'WareHouse Out has been Succesfully Deleted!');
    }

    public function scan($id)
    {
        $scan = Pellete::select('pelletes.pellete_no', 'warehouse_out_details.pellete_id')
        ->join('warehouse_out_details', 'pelletes.id', '=', 'warehouse_out_details.pellete_id')
        ->where('warehouse_out_details.wo_id', $id)
        ->get();
        return view('warehouses.warehouse-out.scan', compact('scan'));
    }
}
