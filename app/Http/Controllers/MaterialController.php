<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Category;
use App\Models\Material;
use App\Models\MaterialIn;
use App\Models\MaterialInventory;
use App\Models\MaterialOut;
use App\Models\ProductionOrderDetail;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Material List') ||
            Auth::user()->hasPermissionTo('Material Create') ||
            Auth::user()->hasPermissionTo('Material Edit') ||
            Auth::user()->hasPermissionTo('Material Delete')
        ) {
            $materials = Material::all();
            Helper::logSystemActivity('Material', 'Material List');
            return view('materials.material.index', compact('materials'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    
    public function create(){
        if (!Auth::user()->hasPermissionTo('Material Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $uoms = Uom::select('id','name')->get();
        $suppliers = Supplier::select('id','name')->get();
        $categories = Category::select('id','name')->get();
        Helper::logSystemActivity('Material', 'Material Create');
        return view('materials.material.create', compact('categories', 'suppliers', 'uoms'));
    }

    public function store(Request $request){

        if (!Auth::user()->hasPermissionTo('Material Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('materials', 'name')->whereNull('deleted_at'),
            ],
            'type' => 'required',
            'category' => 'required',
            'uom' => 'required',
            'supplier' => 'required'
        ]);

        $material = new Material();
        $material->name = $request->name;
        $material->type = $request->type;
        $material->category_id = $request->category;
        $material->uom_ids = json_encode($request->uom);
        $material->supplier_ids = json_encode($request->supplier);
        $material->save();

        $material_inventry = new MaterialInventory;
        $material_inventry->item_id = $material->id;
        $material_inventry->value = 0;
        $material_inventry->save();

        Helper::logSystemActivity('Material', 'Material Create');
        return redirect()->route('material.index')->with('custom_success', 'Material has been Succesfully Created!');
    }

    public function edit($id){

        if (!Auth::user()->hasPermissionTo('Material Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material = Material::find($id);
        $uoms = Uom::select('id','name')->get();
        $suppliers = Supplier::select('id','name')->get();
        $categories = Category::select('id','name')->get();

        Helper::logSystemActivity('Material', 'Material Edit');
        return view('materials.material.edit', compact("material", 'categories', 'suppliers', 'uoms'));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('Material Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('materials', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'type' => 'required',
            'category' => 'required',
            'uom' => 'required',
            'supplier' => 'required'
        ]);

        $material = Material::find($id);
        $material->name = $request->name;
        $material->type = $request->type;
        $material->category_id = $request->category;
        $material->uom_ids = json_encode($request->uom);
        $material->supplier_ids = json_encode($request->supplier);
        $material->save();

        Helper::logSystemActivity('Material', 'Material Update');
        return redirect()->route('material.index')->with('custom_success', 'Material has been Succesfully Updated!');        
    }

    public function  destroy($id){

        if (!Auth::user()->hasPermissionTo('Material Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $material = Material::find($id);
        $Material_in = MaterialIn::where('item_id', '=', $id)->first();
        $Material_out = MaterialOut::where('item_id', '=', $id)->first();
        $Purchase = PurchaseOrder::whereJsonContains('item_id', '=', $id)->first();

        if($Material_in){
            return back()->with('custom_errors', 'This MATERIAL is used in MATERIAL IN!');
        }elseif($Material_out){
            return back()->with('custom_errors', 'This MATERIAL is used in MATERIAL OUT!');
        }elseif($Purchase){
            return back()->with('custom_errors', 'This MATERIAL is used in PRODUCTION ORDER!');
        }
        MaterialInventory::where('item_id','=',$id)->delete();
        $material->delete();
        Helper::logSystemActivity('Material', 'Material Delete');
        return redirect()->route('material.index')->with('custom_success', 'Material has been Succesfully Deleted!');
    }
}
