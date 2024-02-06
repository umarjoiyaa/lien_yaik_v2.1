<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Machine;
use App\Models\Material;
use App\Models\MaterialInventory;
use App\Models\ProductionOrder;
use App\Models\ProductionOrderDetail;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductionOrderController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Production Order List') ||
            Auth::user()->hasPermissionTo('Production Order Create') ||
            Auth::user()->hasPermissionTo('Production Order Edit') ||
            Auth::user()->hasPermissionTo('Production Order Delete')
        ) {
            $productions = ProductionOrder::with('product', 'batch')->get();
            Helper::logSystemActivity('Production Order', 'Production Order List');
            return view('productions.production-order.index', compact('productions'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Production Order Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $orders = PurchaseOrder::select('id', 'order_no')->where('status', 1)->get();
        $batches = Batch::all();
        $machines = Machine::all();

        $materials = Material::select(
            'materials.id',
            'materials.name',
            'materials.type',
            'material_inventories.value',
            'categories.name as category',
            DB::raw('GROUP_CONCAT(DISTINCT uoms.name) as uoms'),
            DB::raw('GROUP_CONCAT(DISTINCT suppliers.name) as suppliers')
        )
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->join('material_inventories', 'materials.id', '=', 'material_inventories.item_id')
            ->join('uoms', function ($join1) {
                $join1->whereRaw('JSON_SEARCH(materials.uom_ids, "one", uoms.id) IS NOT NULL');
            })
            ->join('suppliers', function ($join2) {
                $join2->whereRaw('JSON_SEARCH(materials.supplier_ids, "one", suppliers.id) IS NOT NULL');
            })
            ->groupBy('materials.id', 'materials.name', 'categories.name', 'materials.type', 'material_inventories.value')
            ->get();

        Helper::logSystemActivity('Production Order', 'Production Order Create');
        return view('productions.production-order.create', compact('orders', 'batches', 'machines', 'materials'));
    }

    public function store(Request $request)
    {

        if (!Auth::user()->hasPermissionTo('Production Order Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "purchase_order" => "required",
            "batch" => "required",
            "reject" => "required",
            "used_quantity" => "required",
            "target_date" => "required",
            "machine" => "required",
            "status" => "required",
            "items" => "required"
        ]);

        $production = new ProductionOrder();
        $production->order_id = $request->purchase_order;
        $production->batch_id = $request->batch;
        $production->product_id = $request->product_id;
        $production->operator_id = Auth::user()->id;
        $production->machine_id = json_encode($request->machine);
        $production->order_date = $request->order_date;
        $production->target_produce = $request->target_produce;
        $production->due_date = $request->target_date;
        $production->issued_date = $request->issue_date;
        $production->no_cavity = $request->no_cavity;
        $production->reject_qty = $request->reject;
        $production->weight_unit = $request->weight_unit;
        $production->order_unit = $request->order_unit;
        $production->used_qty = $request->used_quantity;
        $production->available_qty = $request->available_quantity;
        $production->target_need = $request->need_produce;
        $production->weight_mold = $request->weight_mold;
        $production->mold_shot = $request->press;
        $production->raw_material = $request->raw_material;
        $production->status = $request->status;
        $production->save();

        foreach ($request->items as $value) {
            $details = new ProductionOrderDetail();
            $details->po_id = $production->id;
            $details->item_id = $value['id'];
            $details->available = $value['available'] ?? 0;
            $details->required = $value['required'] ?? 0;
            $details->need = $value['need'] ?? 0;
            $details->save();

            $total = ($value['required']) ? $value['required'] : 0;
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();
            $stock->value = (float)$stock->value - (float)$total;
            $stock->save();
        }

        Helper::logSystemActivity('Production Order', 'Production Order Create');
        return redirect()->route('production.index')->with('custom_success', 'Production Order has been Succesfully Created!');
    }

    public function edit($id)
    {

        if (!Auth::user()->hasPermissionTo('Production Order Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $orders = PurchaseOrder::select('id', 'order_no')->where('status', 1)->get();
        $batches = Batch::all();
        $machines = Machine::all();
        $production = ProductionOrder::find($id);
        $details = ProductionOrderDetail::where('po_id', '=', $id)->get();

        $materials = Material::select(
            'materials.id',
            'materials.name',
            'materials.type',
            'material_inventories.value',
            'categories.name as category',
            DB::raw('GROUP_CONCAT(DISTINCT uoms.name) as uoms'),
            DB::raw('GROUP_CONCAT(DISTINCT suppliers.name) as suppliers')
        )
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->join('material_inventories', 'materials.id', '=', 'material_inventories.item_id')
            ->join('uoms', function ($join1) {
                $join1->whereRaw('JSON_SEARCH(materials.uom_ids, "one", uoms.id) IS NOT NULL');
            })
            ->join('suppliers', function ($join2) {
                $join2->whereRaw('JSON_SEARCH(materials.supplier_ids, "one", suppliers.id) IS NOT NULL');
            })
            ->groupBy('materials.id', 'materials.name', 'categories.name', 'materials.type', 'material_inventories.value')
            ->get();

        Helper::logSystemActivity('Production Order', 'Production Order Edit');
        return view('productions.production-order.edit', compact('production', 'orders', 'batches', 'machines', 'details', 'materials'));
    }

    public function update(Request $request, $id)
    {

        if (!Auth::user()->hasPermissionTo('Production Order Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "purchase_order" => "required",
            "batch" => "required",
            "reject" => "required",
            "used_quantity" => "required",
            "target_date" => "required",
            "machine" => "required",
            "status" => "required",
            "items" => "required"
        ]);

        $production = ProductionOrder::find($id);
        $production->order_id = $request->purchase_order;
        $production->batch_id = $request->batch;
        $production->product_id = $request->product_id;
        $production->operator_id = Auth::user()->id;
        $production->machine_id = json_encode($request->machine);
        $production->order_date = $request->order_date;
        $production->target_produce = $request->target_produce;
        $production->due_date = $request->target_date;
        $production->issued_date = $request->issue_date;
        $production->no_cavity = $request->no_cavity;
        $production->reject_qty = $request->reject;
        $production->weight_unit = $request->weight_unit;
        $production->order_unit = $request->order_unit;
        $production->used_qty = $request->used_quantity;
        $production->available_qty = $request->available_quantity;
        $production->target_need = $request->need_produce;
        $production->weight_mold = $request->weight_mold;
        $production->mold_shot = $request->press;
        $production->raw_material = $request->raw_material;
        $production->status = $request->status;
        $production->save();

        foreach ($request->items as $value) {
            $deduct_qty = ProductionOrderDetail::where('po_id', '=', $id)->where('item_id', '=', $value['id'])->first();
            $total = ($value['required']) ? $value['required'] : 0;
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();
            $stock->value = (float)$stock->value - ((float)$total + (float)$deduct_qty->required);
            $stock->save();

            $deduct_qty->delete();

            $details = new ProductionOrderDetail();
            $details->po_id = $production->id;
            $details->item_id = $value['id'];
            $details->available = $value['available'] ?? 0;
            $details->required = $value['required'] ?? 0;
            $details->need = $value['need'] ?? 0;
            $details->save();
        }


        Helper::logSystemActivity('Production Order', 'Production Order Update');
        return redirect()->route('production.index')->with('custom_success', 'Production Order has been Succesfully Updated!');
    }

    public function destroy($id)
    {

        if (!Auth::user()->hasPermissionTo('Production Order Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $production = ProductionOrder::find($id);
        $details = ProductionOrderDetail::where('po_id', '=', $id)->get();
        foreach ($details as $value) {
            $deduct_qty = ProductionOrderDetail::where('po_id', '=', $id)->where('item_id', '=', $value->item_id)->first();
            $stock = MaterialInventory::where('item_id', '=', $value->item_id)->first();
            $stock->value = (float)$stock->value + (float)$deduct_qty->required;
            $stock->save();
        }
        ProductionOrderDetail::where('po_id', '=', $id)->delete();
        $production->delete();
        Helper::logSystemActivity('Production Order', 'Production Order Delete');
        return redirect()->route('production.index')->with('custom_success', 'Production Order has been Succesfully Deleted!');
    }

    public function purchase(Request $request)
    {
        $result = PurchaseOrder::select(
            'purchase_orders.id',
            'purchase_orders.product_id',
            'purchase_orders.order_date',
            'purchase_orders.req_date',
            'purchase_orders.order_unit',
            'purchase_orders.cavities',
            'purchase_orders.unit_kg',
            'purchase_orders.per_mold',
            'products.name as product_name',
            'inventories.value'
        )
            ->join('products', 'purchase_orders.product_id', '=', 'products.id')
            ->leftJoin('inventories', function ($join) {
                $join->on('inventories.product_id', '=', 'purchase_orders.product_id')
                    ->join('production_orders', 'inventories.product_id', '=', 'production_orders.product_id')
                    ->where('production_orders.product_id', '=', DB::raw('purchase_orders.product_id'));
            })
            ->where('purchase_orders.id', $request->id)
            ->first();

        return response()->json($result);
    }
}
