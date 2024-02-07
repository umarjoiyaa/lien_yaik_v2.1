<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Material;
use App\Models\MaterialIn;
use App\Models\MaterialInDetail;
use App\Models\MaterialInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialInController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Material In List') ||
            Auth::user()->hasPermissionTo('Material In Create') ||
            Auth::user()->hasPermissionTo('Material In Update') ||
            Auth::user()->hasPermissionTo('Material In Delete')
        ) {
            $material_ins = MaterialIn::all();
            Helper::logSystemActivity('Material In', 'Material In List');
            return view('materials.material-in.index', compact('material_ins'));
        }
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Material In Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $materials = Material::select(
            'materials.id',
            'materials.name',
            'categories.name as category',
            DB::raw('GROUP_CONCAT(DISTINCT uoms.name) as uoms'),
            DB::raw('GROUP_CONCAT(DISTINCT suppliers.name) as suppliers')
        )
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->join('uoms', function ($join1) {
                $join1->whereRaw('JSON_SEARCH(materials.uom_ids, "one", uoms.id) IS NOT NULL');
            })
            ->join('suppliers', function ($join2) {
                $join2->whereRaw('JSON_SEARCH(materials.supplier_ids, "one", suppliers.id) IS NOT NULL');
            })
            ->groupBy('materials.id', 'materials.name', 'categories.name')
            ->get();

        Helper::logSystemActivity('Material In', 'Material In Create');
        return view('materials.material-in.create', compact('materials'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Material In Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "date" => "required",
            "pic" => "required",
            "items" => "required"
        ]);

        $material_ins = new MaterialIn();
        $material_ins->date = $request->date;
        $material_ins->pic = Auth::user()->id;
        $material_ins->save();

        foreach ($request->items as $value) {
            $material_in_details = new MaterialInDetail();
            $material_in_details->mi_id = $material_ins->id;
            $material_in_details->item_id = $value['id'];
            $material_in_details->qty = ($value['qty'] <= 0) ? 1 : $value['qty'];
            $material_in_details->save();

            $total = ($value['qty'] <= 0) ? 1 : $value['qty'];
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();
            $stock->value = ((float)$stock->value + (float)$total < 0) ? 0 : (float)$stock->value + (float)$total;
            $stock->save();
        }

        Helper::logSystemActivity('Material In', 'Material In Store');
        return redirect()->route('material-in.index')->with('custom_success', 'Material In has been Succesfully Created!');;
    }

    public function edit(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Material In Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material_in = MaterialIn::find($id);
        $details = MaterialInDetail::where('mi_id', '=', $id)->get();
        $materials = Material::select(
            'materials.id',
            'materials.name',
            'categories.name as category',
            DB::raw('GROUP_CONCAT(DISTINCT uoms.name) as uoms'),
            DB::raw('GROUP_CONCAT(DISTINCT suppliers.name) as suppliers')
        )
            ->join('categories', 'materials.category_id', '=', 'categories.id')
            ->join('uoms', function ($join1) {
                $join1->whereRaw('JSON_SEARCH(materials.uom_ids, "one", uoms.id) IS NOT NULL');
            })
            ->join('suppliers', function ($join2) {
                $join2->whereRaw('JSON_SEARCH(materials.supplier_ids, "one", suppliers.id) IS NOT NULL');
            })
            ->groupBy('materials.id', 'materials.name', 'categories.name')
            ->get();

        Helper::logSystemActivity('Material In', 'Material In Edit');
        return view('materials.material-in.edit', compact('materials', 'material_in', 'details'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Material In Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "date" => "required",
            "pic" => "required",
            "items" => "required"
        ]);

        $material_ins = MaterialIn::find($id);
        $material_ins->date = $request->date;
        $material_ins->pic = Auth::user()->id;
        $material_ins->save();

        $materialInDetails = MaterialInDetail::where('mi_id', '=', $id)->get();

        foreach ($request->items as $value) {
            $deductQty = $materialInDetails->where('item_id', '=', $value['id'])->first();
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();

            $total = max($value['qty'], 1); // Ensures total is at least 1

            $retVal = optional($deductQty)->qty ?? 0;

            $stock->update([
                'value' => (($stock->value + ($total - $retVal)) < 0) ? 0 : $stock->value + ($total - $retVal),
            ]);

            if ($deductQty) {
                $deductQty->update([
                    'qty' => $total
                ]);
            } else {
                // If record doesn't exist, create a new one
                MaterialInDetail::create([
                    'mi_id' => $id,
                    'item_id' => $value['id'],
                    'qty' => $total
                ]);
            }
        }

        // Delete unwanted records
        MaterialInDetail::where('mi_id', '=', $id)->whereNotIn('item_id', array_column($request->items, 'id'))->delete();

        Helper::logSystemActivity('Material In', 'Material In Update');
        return redirect()->route('material-in.index')->with('custom_success', 'Material In has been Succesfully Updated!');
    }

    public function destroy($id)
    {

        if (!Auth::user()->hasPermissionTo('Material In Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material_in = MaterialIn::find($id);
        $details = MaterialInDetail::where('mi_id', '=', $id)->get();
        foreach ($details as $value) {
            $deduct_qty = MaterialInDetail::where('mi_id', '=', $id)->where('item_id', '=', $value->item_id)->first();
            $stock = MaterialInventory::where('item_id', '=', $value->item_id)->first();
            $stock->value = ((float)$stock->value - (float)$deduct_qty->qty < 0) ? 0 : (float)$stock->value - (float)$deduct_qty->qty;
            $stock->save();
        }
        MaterialInDetail::where('mi_id', '=', $id)->delete();
        $material_in->delete();
        Helper::logSystemActivity('Material In', 'Material In Delete');
        return redirect()->route('material-in.index')->with('custom_success', 'Material In has been Succesfully Deleted!');
    }
}
