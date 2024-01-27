<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Material;
use App\Models\MaterialInventory;
use App\Models\MaterialOut;
use Illuminate\Http\Request;
use App\Models\MaterialOutDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MaterialOutController extends Controller
{
    public function index()
    {
        if (
            Auth::user()->hasPermissionTo('Material Out List') ||
            Auth::user()->hasPermissionTo('Material Out Create') ||
            Auth::user()->hasPermissionTo('Material Out Update') ||
            Auth::user()->hasPermissionTo('Material Out Delete')
        ) {
            $material_outs = MaterialOut::all();
            Helper::logSystemActivity('Material Out', 'Material Out List');
            return view('materials.material-out.index', compact('material_outs'));
        }
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('Material Out Create')) {
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

        Helper::logSystemActivity('Material Out', 'Material Out Create');
        return view('materials.material-out.create', compact('materials'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->hasPermissionTo('Material Out Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "date" => "required",
            "pic" => "required",
            "items" => "required"
        ]);

        $material_outs = new MaterialOut();
        $material_outs->date = $request->date;
        $material_outs->pic = Auth::user()->id;
        $material_outs->save();

        foreach ($request->items as $value) {
            $material_out_details = new MaterialOutDetail();
            $material_out_details->mo_id = $material_outs->id;
            $material_out_details->item_id = $value['id'];
            $material_out_details->qty = ($value['qty'] <= 0) ? 1 : $value['qty'];
            $material_out_details->save();

            $total = ($value['qty'] <= 0) ? 1 : $value['qty'];
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();
            $stock->value = (float)$stock->value - (float)$total;
            $stock->save();
        }

        Helper::logSystemActivity('Material Out', 'Material Out Store');
        return redirect()->route('material-out.index')->with('custom_success', 'Material Out has been Succesfully Created!');;
    }

    public function edit(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Material Out Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material_out = MaterialOut::find($id);
        $details = MaterialOutDetail::where('mo_id', '=', $id)->get();
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

        Helper::logSystemActivity('Material Out', 'Material Out Edit');
        return view('materials.material-out.edit', compact('materials', 'material_out', 'details'));
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('Material Out Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "date" => "required",
            "pic" => "required",
            "items" => "required"
        ]);

        $material_outs = MaterialOut::find($id);
        $material_outs->date = $request->date;
        $material_outs->pic = Auth::user()->id;
        $material_outs->save();

        $MaterialOutDetail = MaterialOutDetail::where('mo_id', '=', $id)->get();

        foreach ($request->items as $value) {
            $deductQty = $MaterialOutDetail->where('item_id', '=', $value['id'])->first();
            $stock = MaterialInventory::where('item_id', '=', $value['id'])->first();

            $total = max($value['qty'], 1); // Ensures total is at least 1

            $retVal = optional($deductQty)->qty ?? 0;

            $stock->update([
                'value' => $stock->value - ($total - $retVal)
            ]);

            if ($deductQty) {
                $deductQty->update([
                    'qty' => $total
                ]);
            } else {
                // If record doesn't exist, create a new one
                MaterialOutDetail::create([
                    'mo_id' => $id,
                    'item_id' => $value['id'],
                    'qty' => $total
                ]);
            }
        }
        MaterialOutDetail::where('mo_id', '=', $id)->whereNotIn('item_id', array_column($request->items, 'id'))->delete();

        Helper::logSystemActivity('Material Out', 'Material Out Update');
        return redirect()->route('material-out.index')->with('custom_success', 'Material Out has been Succesfully Updated!');
    }

    public function destroy($id)
    {

        if (!Auth::user()->hasPermissionTo('Material Out Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material_out = MaterialOut::find($id);
        $details = MaterialOutDetail::where('mo_id', '=', $id)->get();
        foreach ($details as $value) {
            $deduct_qty = MaterialOutDetail::where('mo_id', '=', $id)->where('item_id', '=', $value->item_id)->first();
            $stock = MaterialInventory::where('item_id', '=', $value->item_id)->first();
            $stock->value = (float)$stock->value + (float)$deduct_qty->qty;
            $stock->save();
        }
        MaterialOutDetail::where('mo_id', '=', $id)->delete();
        $material_out->delete();
        Helper::logSystemActivity('Material Out', 'Material Out Delete');
        return redirect()->route('material-out.index')->with('custom_success', 'Material Out has been Succesfully Deleted!');
    }
}
