<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Drilling;
use App\Models\DrillingGoodPellete;
use App\Models\DrillingPellete;
use App\Models\DrillingRejectPellete;
use App\Models\Pellete;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Shotblast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DrillingController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Drilling List') ||
            Auth::user()->hasPermissionTo('Drilling Create') ||
            Auth::user()->hasPermissionTo('Drilling Edit') ||
            Auth::user()->hasPermissionTo('Drilling Delete')
        ) {
            $drillings = Drilling::all();
            Helper::logSystemActivity('Drilling', 'Drilling List');
            return view('productions.work-in-progress.drilling.index', compact('drillings'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Drilling Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutDrilling = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) {
                $query->select('drillings.batch_id')->from('drillings')
                ->whereNull('drillings.deleted_at');
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutDrilling)->get();
        Helper::logSystemActivity('Drilling', 'Drilling Create');
        return view('productions.work-in-progress.drilling.create',compact('pelletes', "products" , 'batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Drilling Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $drilling = new Drilling();
        $drilling->date = $request->date;
        $drilling->remarks = $request->remarks;
        $drilling->batch_id = $request->batch_no;
        $drilling->operator_id = Auth::user()->id;
        $drilling->save();

        foreach ($request->shotblast as $value) {
            $drilling_pelletes = new DrillingPellete();
            $drilling_pelletes->pellete_id = $value['id'];
            $drilling_pelletes->dr_id = $drilling->id;
            $drilling_pelletes->weight = $value['weight'] ?? 0;
            $drilling_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_pelletes->save();
        }

        foreach ($request->good as $value) {
            $drilling_good_pelletes = new DrillingGoodPellete();
            $drilling_good_pelletes->pellete_id = $value['id'];
            $drilling_good_pelletes->dr_id = $drilling->id;
            $drilling_good_pelletes->weight = $value['weight'] ?? 0;
            $drilling_good_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_good_pelletes->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Drilling';
            $pellete->save();
        }

        foreach ($request->reject as $value) {
            $drilling_reject_pelletes = new DrillingRejectPellete();
            $drilling_reject_pelletes->pellete_id = $value['id'];
            $drilling_reject_pelletes->dr_id = $drilling->id;
            $drilling_reject_pelletes->weight = $value['weight'] ?? 0;
            $drilling_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_reject_pelletes->save();
        }

        Helper::logSystemActivity('Drilling', 'Drilling Store');
        return redirect()->route('drilling.index')->with('custom_success', 'Drilling has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Drilling Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutDrilling = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) use ($id) {
                $query->select('drillings.batch_id')->from('drillings')
                ->whereNull('drillings.deleted_at')
                ->where('drillings.batch_id', '!=', $id);
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutDrilling)->get();
        $drilling = Drilling::find($id);
        $shotblasts = DrillingPellete::where('dr_id', '=', $id)->get();
        $goods = DrillingGoodPellete::where('dr_id', '=', $id)->get();
        $rejects = DrillingRejectPellete::where('dr_id', '=', $id)->get();

        Helper::logSystemActivity('Drilling', 'Drilling Edit');
        return view('productions.work-in-progress.drilling.edit',compact('pelletes', "products" , 'batches', 'shotblasts', 'goods', 'rejects', 'drilling'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('Drilling Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $drilling = Drilling::find($id);
        $drilling->date = $request->date;
        $drilling->remarks = $request->remarks;
        $drilling->batch_id = $request->batch_no;
        $drilling->operator_id = Auth::user()->id;
        $drilling->save();

        DrillingPellete::where('dr_id', '=', $id)->delete();

        foreach ($request->shotblast as $value) {
            $drilling_pelletes = new DrillingPellete();
            $drilling_pelletes->pellete_id = $value['id'];
            $drilling_pelletes->dr_id = $drilling->id;
            $drilling_pelletes->weight = $value['weight'] ?? 0;
            $drilling_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_pelletes->save();
        }

        DrillingGoodPellete::where('dr_id', '=', $id)->delete();

        foreach ($request->good as $value) {
            $drilling_good_pelletes = new DrillingGoodPellete();
            $drilling_good_pelletes->pellete_id = $value['id'];
            $drilling_good_pelletes->dr_id = $drilling->id;
            $drilling_good_pelletes->weight = $value['weight'] ?? 0;
            $drilling_good_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_good_pelletes->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Drilling';
            $pellete->save();
        }

        DrillingRejectPellete::where('dr_id', '=', $id)->delete();

        foreach ($request->reject as $value) {
            $drilling_reject_pelletes = new DrillingRejectPellete();
            $drilling_reject_pelletes->pellete_id = $value['id'];
            $drilling_reject_pelletes->dr_id = $drilling->id;
            $drilling_reject_pelletes->weight = $value['weight'] ?? 0;
            $drilling_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $drilling_reject_pelletes->save();
        }

        Helper::logSystemActivity('Drilling', 'Drilling Update');
        return redirect()->route('drilling.index')->with('custom_success', 'Drilling has been Succesfully Updated!');
    }

    public function destroy($id){
        $drilling = Drilling::find($id);
        DrillingPellete::where('dr_id', '=', $id)->delete();
        DrillingGoodPellete::where('dr_id', '=', $id)->delete();
        DrillingRejectPellete::where('dr_id', '=', $id)->delete();
        $drilling->delete();
        Helper::logSystemActivity('Drilling', 'Drilling Delete');
        return redirect()->route('drilling.index')->with('custom_success', 'Drilling has been Succesfully Deleted!');
    }

    public function pelletes(Request $request)
    {
        if($request->status == 1){
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');
            return response()->json(['production' => $production, 'weight_unit' => $weight_unit]);
        }else{
            $shotblast = Shotblast::where('batch_id', $request->id)->first();
            $drillings = Drilling::where('batch_id', $request->id)->get();

            $query = 'SELECT DISTINCT pelletes.id, pelletes.pellete_no, shotblast_details.weight, shotblast_details.pcs FROM shotblast_details INNER JOIN pelletes ON shotblast_details.pellete_id = pelletes.id WHERE sb_id =' . $shotblast->id;
            
            foreach ($drillings as $drilling) {
                $query .= ' UNION SELECT DISTINCT pelletes.id, pelletes.pellete_no, drilling_good_pelletes.weight, drilling_good_pelletes.pcs FROM drilling_good_pelletes INNER JOIN pelletes ON drilling_good_pelletes.pellete_id = pelletes.id WHERE dr_id =' . $drilling->id . ' AND drilling_good_pelletes.deleted_at IS NULL AND shotblast_details.deleted_at IS NULL';
            }
            
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');

            $data = DB::select($query);
            
            return response()->json(['shotblast' => $data, 'production' => $production, 'weight_unit' => $weight_unit]);
        }
    }
}
