<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Material;
use App\Models\Uom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UomController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('UOM List') ||
            Auth::user()->hasPermissionTo('UOM Create') ||
            Auth::user()->hasPermissionTo('UOM Edit') ||
            Auth::user()->hasPermissionTo('UOM Delete')
        ) {
            $uoms = Uom::all();
            Helper::logSystemActivity('UOM', 'UOM List');
            return view('materials.uom.index', compact('uoms'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    
    public function create(){
        if (!Auth::user()->hasPermissionTo('UOM Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        Helper::logSystemActivity('UOM', 'UOM Create');
        return view('materials.uom.create');
    }

    public function store(Request $request){

        if (!Auth::user()->hasPermissionTo('UOM Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('uoms', 'name')->whereNull('deleted_at'),
            ],
            'symbol' => 'required'
        ]);

        $uom = new UOM();
        $uom->name = $request->name;
        $uom->symbol = $request->symbol;
        $uom->save();
        Helper::logSystemActivity('UOM', 'UOM Create');
        return redirect()->route('uom.index')->with('custom_success', 'UOM has been Succesfully Created!');
    }

    public function edit($id){

        if (!Auth::user()->hasPermissionTo('UOM Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $uom = UOM::find($id);
        Helper::logSystemActivity('UOM', 'UOM Edit');
        return view('materials.uom.edit', compact("uom"));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('UOM Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('uoms', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'symbol' => 'required'
        ]);

        $uom = UOM::find($id);
        $uom->name = $request->name;
        $uom->symbol = $request->symbol;
        $uom->save();
        Helper::logSystemActivity('UOM', 'UOM Update');
        return redirect()->route('uom.index')->with('custom_success', 'UOM has been Succesfully Updated!');        
    }

    public function  destroy($id){

        if (!Auth::user()->hasPermissionTo('UOM Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $uom = UOM::find($id);
        $Materials = Material::whereJsonContains('uom_id', '=', $id)->first();
        if($Materials){
            return back()->with('custom_errors', 'This UOM is used in MATERIAL!');
        }
        $uom->delete();
        Helper::logSystemActivity('UOM', 'UOM Delete');
        return redirect()->route('uom.index')->with('custom_success', 'UOM has been Succesfully Deleted!');
    }

}
