<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\DrillingGoodPellete;
use App\Models\DrillingPellete;
use App\Models\DrillingRejectPellete;
use App\Models\FinalCheckingGoodPellete;
use App\Models\FinalCheckingPellete;
use App\Models\FinalCheckingRejectPellete;
use App\Models\GrindingGoodPellete;
use App\Models\GrindingPellete;
use App\Models\GrindingRejectPellete;
use App\Models\Inventory;
use App\Models\Pellete;
use App\Models\Shotblast;
use App\Models\ShotblastDetail;
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
        $Shot_blast = ShotblastDetail::where('pellete_id', '=', $id)->first();
        $Grinding_pellete = GrindingPellete::where('pellete_id', '=', $id)->first();
        $Grinding_good_pellete = GrindingGoodPellete::where('pellete_id', '=', $id)->first();
        $Grinding_reject_pellete = GrindingRejectPellete::where('pellete_id', '=', $id)->first();
        $Drilling_pellete = DrillingPellete::where('pellete_id', '=', $id)->first();
        $Drilling_good_pellete = DrillingGoodPellete::where('pellete_id', '=', $id)->first();
        $Drilling_reject_pellete = DrillingRejectPellete::where('pellete_id', '=', $id)->first();
        $FinalChecking_pellete = FinalCheckingPellete::where('pellete_id', '=', $id)->first();
        $FinalChecking_good_pellete = FinalCheckingGoodPellete::where('pellete_id', '=', $id)->first();
        $FinalChecking_reject_pellete = FinalCheckingRejectPellete::where('pellete_id', '=', $id)->first();

        if($Shot_blast){
            return back()->with('custom_errors', 'This PELLETE is used in SHOTBLAST!');
        }elseif($Grinding_pellete || $Grinding_good_pellete || $Grinding_reject_pellete){
            return back()->with('custom_errors', 'This PELLETE is used in GRINDING!');
        }elseif($Drilling_pellete || $Drilling_good_pellete || $Drilling_reject_pellete){
            return back()->with('custom_errors', 'This PELLETE is used in DRILLING!');
        }elseif($FinalChecking_pellete || $FinalChecking_good_pellete || $FinalChecking_reject_pellete){
            return back()->with('custom_errors', 'This PELLETE is used in FINAL CHECKING!');
        }
        $pellete->delete();

        Helper::logSystemActivity('Pellete', 'Pellete Delete');
        return redirect()->route('pellete.index')->with('custom_success', 'Pellete has been Succesfully Deleted!');
    }
}
