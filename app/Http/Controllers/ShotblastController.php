<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Pellete;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use App\Models\Shotblast;
use App\Models\ShotblastDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShotblastController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Shotblast List') ||
            Auth::user()->hasPermissionTo('Shotblast Create') ||
            Auth::user()->hasPermissionTo('Shotblast Edit') ||
            Auth::user()->hasPermissionTo('Shotblast Delete')
        ) {
            $shotblasts = Shotblast::all();
            Helper::logSystemActivity('Shotblast', 'Shotblast List');
            return view('productions.shotblast.index', compact('shotblasts'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Shotblast Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutShotblast = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) {
                $query->select('shotblasts.batch_id')->from('shotblasts')
                ->whereNull('shotblasts.deleted_at');
            })
            ->pluck('production_orders.batch_id');

        $batches = Batch::whereIn('id', $batchIdsWithoutShotblast)->get();
        
        Helper::logSystemActivity('Shotblast', 'Shotblast Create');
        return view('productions.shotblast.create',compact('pelletes', 'batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Shotblast Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "tulang"  => "required",
            "reject"  => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $shotblast = new ShotBlast();
        $shotblast->batch_id = $request->batch_no;
        $shotblast->waste = $request->tulang;
        $shotblast->defect = $request->reject;
        $shotblast->date = $request->date;
        $shotblast->operator_id = Auth::user()->id;
        $shotblast->save();

        foreach ($request->pelletes as $value) {
            $detail = new ShotblastDetail();
            $detail->pellete_id = $value['id'];
            $detail->weight = $value['weight'] ?? 0;
            $detail->pcs = $value['pcs'] ?? 0;
            $detail->sb_id = $shotblast->id;
            $detail->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Shotblast';
            $pellete->save();
        }

        Helper::logSystemActivity('Shotblast', 'Shotblast Store');
        return redirect()->route('shotblast.index')->with('custom_success', 'Shotblast has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Shotblast Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutShotblast = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) use ($id) {
                $query->select('shotblasts.batch_id')->from('shotblasts')
                ->whereNull('shotblasts.deleted_at')
                ->where('shotblasts.batch_id', '!=', $id);
            })
            ->pluck('production_orders.batch_id');

        $batches = Batch::whereIn('id', $batchIdsWithoutShotblast)->get();
        $shotblast = Shotblast::find($id);
        $details = ShotblastDetail::where('sb_id', '=', $id)->with('pellete')->get();

        Helper::logSystemActivity('Shotblast', 'Shotblast Edit');
        return view('productions.shotblast.edit',compact('pelletes', 'batches', 'shotblast', 'details'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('Shotblast Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "tulang"  => "required",
            "reject"  => "required",
            "date"  => "required",
            "pelletes"  => "required"
        ]);

        $shotblast = ShotBlast::find($id);
        $shotblast->batch_id = $request->batch_no;
        $shotblast->waste = $request->tulang;
        $shotblast->defect = $request->reject;
        $shotblast->date = $request->date;
        $shotblast->operator_id = Auth::user()->id;
        $shotblast->save();

        ShotblastDetail::where('sb_id', '=', $id)->delete();

        foreach ($request->pelletes as $value) {
            $detail = new ShotblastDetail();
            $detail->pellete_id = $value['id'];
            $detail->weight = $value['weight'] ?? 0;
            $detail->pcs = $value['pcs'] ?? 0;
            $detail->sb_id = $shotblast->id;
            $detail->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Shotblast';
            $pellete->save();
        }

        Helper::logSystemActivity('Shotblast', 'Shotblast Update');
        return redirect()->route('shotblast.index')->with('custom_success', 'Shotblast has been Succesfully Updated!');
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Shotblast Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $shotblast = ShotBlast::find($id);
        ShotblastDetail::where('sb_id', '=', $id)->delete();
        $shotblast->delete();
        Helper::logSystemActivity('Shotblast', 'Shotblast Delete');
        return redirect()->route('shotblast.index')->with('custom_success', 'Shotblast has been Succesfully Deleted!');
    }
}
