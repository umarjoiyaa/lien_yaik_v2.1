<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Supplier List') ||
            Auth::user()->hasPermissionTo('Supplier Create') ||
            Auth::user()->hasPermissionTo('Supplier Edit') ||
            Auth::user()->hasPermissionTo('Supplier Delete')
        ) {
            $suppliers = Supplier::all();
            Helper::logSystemActivity('Supplier', 'Supplier List');
            return view('materials.supplier.index', compact('suppliers'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }
    
    public function create(){
        if (!Auth::user()->hasPermissionTo('Supplier Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        Helper::logSystemActivity('Supplier', 'Supplier Create');
        return view('materials.supplier.create');
    }

    public function store(Request $request){

        if (!Auth::user()->hasPermissionTo('Supplier Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('suppliers', 'name')->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                Rule::unique('suppliers', 'code')->whereNull('deleted_at'),
            ],
        ]);

        $supplier = new Supplier();
        $supplier->name = $request->name;
        $supplier->code = $request->code;
        $supplier->save();
        Helper::logSystemActivity('Supplier', 'Supplier Create');
        return redirect()->route('supplier.index')->with('custom_success', 'Supplier has been Succesfully Created!');
    }

    public function edit($id){

        if (!Auth::user()->hasPermissionTo('Supplier Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $supplier = Supplier::find($id);
        Helper::logSystemActivity('Supplier', 'Supplier Edit');
        return view('materials.supplier.edit', compact("supplier"));
    }

    public function update(Request $request, $id){

        if (!Auth::user()->hasPermissionTo('Supplier Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'name' => [
                'required',
                Rule::unique('suppliers', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'code' => [
                'required',
                Rule::unique('suppliers', 'code')->whereNull('deleted_at')->ignore($id),
            ],
        ]);

        $supplier = Supplier::find($id);
        $supplier->name = $request->name;
        $supplier->code = $request->code;
        $supplier->save();
        Helper::logSystemActivity('Supplier', 'Supplier Update');
        return redirect()->route('supplier.index')->with('custom_success', 'Supplier has been Succesfully Updated!');        
    }

    public function  destroy($id){

        if (!Auth::user()->hasPermissionTo('Supplier Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $supplier = Supplier::find($id);
        $supplier->delete();
        Helper::logSystemActivity('Supplier', 'Supplier Delete');
        return redirect()->route('supplier.index')->with('custom_success', 'Supplier has been Succesfully Deleted!');
    }
}
