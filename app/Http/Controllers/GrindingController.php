<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Helpers\Helper;
use App\Models\Pellete;
use App\Models\Product;
use App\Models\Grinding;
use Illuminate\Http\Request;
use App\Models\GrindingPellete;
use App\Models\ProductionOrder;
use App\Models\GrindingGoodPellete;
use App\Models\GrindingRejectPellete;
use App\Models\Shotblast;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GrindingController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Grinding List') ||
            Auth::user()->hasPermissionTo('Grinding Create') ||
            Auth::user()->hasPermissionTo('Grinding Edit') ||
            Auth::user()->hasPermissionTo('Grinding Delete')
        ) {
            $grindings = Grinding::all();
            Helper::logSystemActivity('Grinding', 'Grinding List');
            return view('productions.work-in-progress.grinding.index', compact('grindings'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Grinding Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutGrinding = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) {
                $query->select('grindings.batch_id')->from('grindings')
                ->whereNull('grindings.deleted_at');
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutGrinding)->get();
        Helper::logSystemActivity('Grinding', 'Grinding Create');
        return view('productions.work-in-progress.grinding.create',compact('pelletes', "products" , 'batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Grinding Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $grinding = new Grinding();
        $grinding->date = $request->date;
        $grinding->remarks = $request->remarks;
        $grinding->batch_id = $request->batch_no;
        $grinding->operator_id = Auth::user()->id;
        $grinding->save();

        foreach ($request->shotblast as $value) {
            $grinding_pelletes = new GrindingPellete();
            $grinding_pelletes->pellete_id = $value['id'];
            $grinding_pelletes->gr_id = $grinding->id;
            $grinding_pelletes->weight = $value['weight'] ?? 0;
            $grinding_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_pelletes->save();
        }

        foreach ($request->good as $value) {
            $grinding_good_pelletes = new GrindingGoodPellete();
            $grinding_good_pelletes->pellete_id = $value['id'];
            $grinding_good_pelletes->gr_id = $grinding->id;
            $grinding_good_pelletes->weight = $value['weight'] ?? 0;
            $grinding_good_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_good_pelletes->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Grinding';
            $pellete->save();
        }

        foreach ($request->reject as $value) {
            $grinding_reject_pelletes = new GrindingRejectPellete();
            $grinding_reject_pelletes->pellete_id = $value['id'];
            $grinding_reject_pelletes->gr_id = $grinding->id;
            $grinding_reject_pelletes->weight = $value['weight'] ?? 0;
            $grinding_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_reject_pelletes->save();
        }

        Helper::logSystemActivity('Grinding', 'Grinding Store');
        return redirect()->route('grinding.index')->with('custom_success', 'Grinding has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Grinding Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutGrinding = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) use ($id) {
                $query->select('grindings.batch_id')->from('grindings')
                ->whereNull('grindings.deleted_at')
                ->where('grindings.batch_id', '!=', $id);
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutGrinding)->get();
        $grinding = Grinding::find($id);
        $shotblasts = GrindingPellete::where('gr_id', '=', $id)->get();
        $goods = GrindingGoodPellete::where('gr_id', '=', $id)->get();
        $rejects = GrindingRejectPellete::where('gr_id', '=', $id)->get();

        Helper::logSystemActivity('Grinding', 'Grinding Edit');
        return view('productions.work-in-progress.grinding.edit',compact('pelletes', "products" , 'batches', 'shotblasts', 'goods', 'rejects', 'grinding'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('Grinding Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $grinding = Grinding::find($id);
        $grinding->date = $request->date;
        $grinding->remarks = $request->remarks;
        $grinding->batch_id = $request->batch_no;
        $grinding->operator_id = Auth::user()->id;
        $grinding->save();

        GrindingPellete::where('gr_id', '=', $id)->delete();

        foreach ($request->shotblast as $value) {
            $grinding_pelletes = new GrindingPellete();
            $grinding_pelletes->pellete_id = $value['id'];
            $grinding_pelletes->gr_id = $grinding->id;
            $grinding_pelletes->weight = $value['weight'] ?? 0;
            $grinding_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_pelletes->save();
        }

        GrindingGoodPellete::where('gr_id', '=', $id)->delete();

        foreach ($request->good as $value) {
            $grinding_good_pelletes = new GrindingGoodPellete();
            $grinding_good_pelletes->pellete_id = $value['id'];
            $grinding_good_pelletes->gr_id = $grinding->id;
            $grinding_good_pelletes->weight = $value['weight'] ?? 0;
            $grinding_good_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_good_pelletes->save();

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Grinding';
            $pellete->save();
        }

        GrindingRejectPellete::where('gr_id', '=', $id)->delete();

        foreach ($request->reject as $value) {
            $grinding_reject_pelletes = new GrindingRejectPellete();
            $grinding_reject_pelletes->pellete_id = $value['id'];
            $grinding_reject_pelletes->gr_id = $grinding->id;
            $grinding_reject_pelletes->weight = $value['weight'] ?? 0;
            $grinding_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $grinding_reject_pelletes->save();
        }

        Helper::logSystemActivity('Grinding', 'Grinding Update');
        return redirect()->route('grinding.index')->with('custom_success', 'Grinding has been Succesfully Updated!');
    }

    public function destroy($id){
        $grinding = Grinding::find($id);
        GrindingPellete::where('gr_id', '=', $id)->delete();
        GrindingGoodPellete::where('gr_id', '=', $id)->delete();
        GrindingRejectPellete::where('gr_id', '=', $id)->delete();
        $grinding->delete();
        Helper::logSystemActivity('Grinding', 'Grinding Delete');
        return redirect()->route('grinding.index')->with('custom_success', 'Grinding has been Succesfully Deleted!');
    }

    public function pelletes(Request $request)
    {
        if($request->status == 1){
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');
            return response()->json(['production' => $production, 'weight_unit' => $weight_unit]);
        }else{
            $shotblast = Shotblast::where('batch_id', $request->id)->first();
            $grindings = Grinding::where('batch_id', $request->id)->get();

            $query = 'SELECT DISTINCT pelletes.id, pelletes.pellete_no, shotblast_details.weight, shotblast_details.pcs FROM shotblast_details INNER JOIN pelletes ON shotblast_details.pellete_id = pelletes.id WHERE sb_id =' . $shotblast->id;
            
            foreach ($grindings as $grinding) {
                $query .= ' UNION SELECT DISTINCT pelletes.id, pelletes.pellete_no, grinding_good_pelletes.weight, grinding_good_pelletes.pcs FROM grinding_good_pelletes INNER JOIN pelletes ON grinding_good_pelletes.pellete_id = pelletes.id WHERE gr_id =' . $grinding->id . ' AND grinding_good_pelletes.deleted_at IS NULL AND shotblast_details.deleted_at IS NULL';
            }
            
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');

            $data = DB::select($query);
            
            return response()->json(['shotblast' => $data, 'production' => $production, 'weight_unit' => $weight_unit]);
        }
    }
}
