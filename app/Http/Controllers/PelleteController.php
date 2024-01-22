<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Inventory;
use App\Models\Pellete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PelleteController extends Controller
{
    public function index(){
        if(
            Auth::user()->hasPermissionTo('Pellete List') ||
            Auth::user()->hasPermissionTo('Pellete Create') ||
            Auth::user()->hasPermissionTo('Pellete Edit') ||
            Auth::user()->hasPermissionTo('Pellete Delete')
        ){
            $pelletes = Pellete::all();
            Helper::logSystemActivity('Pellete', 'Pellete List');
            return view('warehouses.pellete.index', compact('pelletes'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Pellete Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        Helper::logSystemActivity('Pellete', 'Pellete Create');
        return view('warehouses.pellete.create');
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Pellete Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'pellete_no' => [
                'required',
                Rule::unique('pelletes', 'pellete_no')->whereNull('deleted_at'),
            ],
            "qr_code" => "required"
        ]);

        $pellete = new Pellete();
        $pellete->pellete_no = $request->pellete_no;
        $pellete->qr_code = $request->qr_code;
        $pellete->save();

        $inventory = new Inventory;
        $inventory->pellete_id = $pellete->id;
        $inventory->save();
        
        Helper::logSystemActivity('Pellete', 'Pellete Store');
        return redirect()->route('pellete.index')->with('custom_success', 'Pellete has been Succesfully Created!');    }

    public function edit($id){
        if(!Auth::user()->hasPermissionTo('Pellete Edit')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pellete = Pellete::with('batches')->find($id);
        Helper::logSystemActivity('Pellete', 'Pellete Edit');
        return view('warehouses.pellete.edit', compact('pellete'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Pellete Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $request->validate([
            'pellete_no' => [
                'required',
                Rule::unique('pelletes', 'pellete_no')->whereNull('deleted_at')->ignore($id),
            ],
            "qr_code" => "required"
        ]);

        $pellete = Pellete::find($id);
        $pellete->pellete_no = $request->pellete_no;
        $pellete->qr_code = $request->qr_code;
        $pellete->save();

        Helper::logSystemActivity('Pellete', 'Pellete Update');
        return redirect()->route('pellete.index')->with('custom_success', 'Pellete has been Succesfully Updated!');
    }

    public function destroy($id){
        if(!Auth::user()->hasPermissionTo('Pellete Delete')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $pellete = Pellete::find($id);
        $pellete->delete();

        Helper::logSystemActivity('Pellete', 'Pellete Delete');
        return redirect()->route('pellete.index')->with('custom_success', 'Pellete has been Succesfully Deleted!');
    }
}
