<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\FinalChecking;
use App\Models\FinalCheckingGoodPellete;
use App\Models\FinalCheckingPellete;
use App\Models\FinalCheckingRejectPellete;
use App\Models\Pellete;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\Shotblast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinalCheckingController extends Controller
{
    public function index(){
        if (
            Auth::user()->hasPermissionTo('Final Checking List') ||
            Auth::user()->hasPermissionTo('Final Checking Create') ||
            Auth::user()->hasPermissionTo('Final Checking Edit') ||
            Auth::user()->hasPermissionTo('Final Checking Delete')
        ) {
            $final_checkings = FinalChecking::all();
            Helper::logSystemActivity('Final Checking', 'Final Checking List');
            return view('productions.work-in-progress.final-checking.index', compact('final_checkings'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Final Checking Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();

        $batchIdsWithoutFinalChecking = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) {
                $query->select('final_checkings.batch_id')->from('final_checkings')
                ->whereNull('final_checkings.deleted_at');
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutFinalChecking)->get();
        Helper::logSystemActivity('Final Checking', 'Final Checking Create');
        return view('productions.work-in-progress.final-checking.create',compact('pelletes', "products" , 'batches'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Final Checking Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $final_checking = new FinalChecking();
        $final_checking->date = $request->date;
        $final_checking->remarks = $request->remarks;
        $final_checking->batch_id = $request->batch_no;
        $final_checking->operator_id = Auth::user()->id;
        $final_checking->save();

        $total_good_weight = 0;
        $total_good_pcs = 0;

        foreach ($request->shotblast as $value) {
            $final_checking_pelletes = new FinalCheckingPellete();
            $final_checking_pelletes->pellete_id = $value['id'];
            $final_checking_pelletes->fc_id = $final_checking->id;
            $final_checking_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_pelletes->save();
        }

        foreach ($request->good as $value) {
            $final_checking_good_pelletes = new FinalCheckingGoodPellete();
            $final_checking_good_pelletes->pellete_id = $value['id'];
            $final_checking_good_pelletes->fc_id = $final_checking->id;
            $final_checking_good_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_good_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_good_pelletes->save();

            $total_good_weight += $value['weight'] ?? 0;
            $total_good_pcs += $value['pcs'] ?? 0;

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Final Checking';
            $pellete->save();
        }

        $final_checking_pelletes_update = FinalChecking::find($final_checking->id);
        $final_checking_pelletes_update->total_good_weight = $total_good_weight;
        $final_checking_pelletes_update->total_good_pcs = $total_good_pcs;
        $final_checking_pelletes_update->save();

        foreach ($request->reject as $value) {
            $final_checking_reject_pelletes = new FinalCheckingRejectPellete();
            $final_checking_reject_pelletes->pellete_id = $value['id'];
            $final_checking_reject_pelletes->fc_id = $final_checking->id;
            $final_checking_reject_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_reject_pelletes->save();
        }

        Helper::logSystemActivity('Final Checking', 'Final Checking Store');
        return redirect()->route('final-checking.index')->with('custom_success', 'Final Checking has been Succesfully Created!');
    }

    public function edit($id){
        if (!Auth::user()->hasPermissionTo('Final Checking Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $pelletes = Pellete::select('id', 'pellete_no')->get();
        $final_checking = FinalChecking::find($id);

        $batchIdsWithoutFinalChecking = ProductionOrder::join('purchase_orders', 'purchase_orders.id', '=', 'production_orders.order_id')
            ->whereNotIn('production_orders.batch_id', function ($query) use ($final_checking) {
                $query->select('final_checkings.batch_id')->from('final_checkings')
                ->whereNull('final_checkings.deleted_at')
                ->where('final_checkings.batch_id', '!=', $final_checking->id);
            })
            ->pluck('production_orders.batch_id');
        $products = Product::find('id', 'name');
        $batches = Batch::whereIn('id', $batchIdsWithoutFinalChecking)->get();
        $shotblasts = FinalCheckingPellete::where('fc_id', '=', $id)->get();
        $goods = FinalCheckingGoodPellete::where('fc_id', '=', $id)->get();
        $rejects = FinalCheckingRejectPellete::where('fc_id', '=', $id)->get();

        Helper::logSystemActivity('FinalChecking', 'FinalChecking Edit');
        return view('productions.work-in-progress.final-checking.edit',compact('pelletes', "products" , 'batches', 'shotblasts', 'goods', 'rejects', 'final_checking'));
    }

    public function update(Request $request,$id){
        if (!Auth::user()->hasPermissionTo('Final Checking Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "batch_no" => "required",
            "date"  => "required",
            "shotblast"  => "required",
            "good"  => "required",
            "reject"  => "required"
        ]);

        $final_checking = FinalChecking::find($id);
        $final_checking->date = $request->date;
        $final_checking->remarks = $request->remarks;
        $final_checking->batch_id = $request->batch_no;
        $final_checking->operator_id = Auth::user()->id;
        $final_checking->save();

        FinalCheckingPellete::where('fc_id', '=', $id)->delete();

        $total_good_weight = 0;
        $total_good_pcs = 0;

        foreach ($request->shotblast as $value) {
            $final_checking_pelletes = new FinalCheckingPellete();
            $final_checking_pelletes->pellete_id = $value['id'];
            $final_checking_pelletes->fc_id = $final_checking->id;
            $final_checking_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_pelletes->save();
        }

        FinalCheckingGoodPellete::where('fc_id', '=', $id)->delete();

        foreach ($request->good as $value) {
            $final_checking_good_pelletes = new FinalCheckingGoodPellete();
            $final_checking_good_pelletes->pellete_id = $value['id'];
            $final_checking_good_pelletes->fc_id = $final_checking->id;
            $final_checking_good_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_good_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_good_pelletes->save();

            $total_good_weight += $value['weight'] ?? 0;
            $total_good_pcs += $value['pcs'] ?? 0;

            $pellete = Pellete::find($value['id']);
            $pellete->batch = $request->batch_no;
            $pellete->weight = $value['weight'];
            $pellete->pcs = $value['pcs'];
            $pellete->status = 'Final Checking';
            $pellete->save();
        }

        $final_checking_pelletes_update = FinalChecking::find($final_checking->id);
        $final_checking_pelletes_update->total_good_weight = $total_good_weight;
        $final_checking_pelletes_update->total_good_pcs = $total_good_pcs;
        $final_checking_pelletes_update->save();

        FinalCheckingRejectPellete::where('fc_id', '=', $id)->delete();

        foreach ($request->reject as $value) {
            $final_checking_reject_pelletes = new FinalCheckingRejectPellete();
            $final_checking_reject_pelletes->pellete_id = $value['id'];
            $final_checking_reject_pelletes->fc_id = $final_checking->id;
            $final_checking_reject_pelletes->weight = $value['weight'] ?? 0;
            $final_checking_reject_pelletes->pcs = $value['pcs'] ?? 0;
            $final_checking_reject_pelletes->save();
        }

        Helper::logSystemActivity('Final Checking', 'Final Checking Update');
        return redirect()->route('final-checking.index')->with('custom_success', 'Final Checking has been Succesfully Updated!');
    }

    public function destroy($id){
        if (!Auth::user()->hasPermissionTo('Final Checking Delete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $final_checking = FinalChecking::find($id);
        FinalCheckingPellete::where('fc_id', '=', $id)->delete();
        FinalCheckingGoodPellete::where('fc_id', '=', $id)->delete();
        FinalCheckingRejectPellete::where('fc_id', '=', $id)->delete();
        $final_checking->delete();
        Helper::logSystemActivity('Final Checking', 'Final Checking Delete');
        return redirect()->route('final-checking.index')->with('custom_success', 'Final Checking has been Succesfully Deleted!');
    }

    public function pelletes(Request $request)
    {
        if($request->status == 1){
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');
            return response()->json(['production' => $production, 'weight_unit' => $weight_unit]);
        }else{
            $shotblast = Shotblast::where('batch_id', $request->id)->first();
            $final_checkings = FinalChecking::where('batch_id', $request->id)->get();

            $query = 'SELECT DISTINCT pelletes.id, pelletes.pellete_no, shotblast_details.weight, shotblast_details.pcs FROM shotblast_details INNER JOIN pelletes ON shotblast_details.pellete_id = pelletes.id WHERE sb_id =' . $shotblast->id . ' AND shotblast_details.deleted_at IS NULL';
            
            foreach ($final_checkings as $final_checking) {
                $query .= ' UNION SELECT DISTINCT pelletes.id, pelletes.pellete_no, final_checking_good_pelletes.weight, final_checking_good_pelletes.pcs FROM final_checking_good_pelletes INNER JOIN pelletes ON final_checking_good_pelletes.pellete_id = pelletes.id WHERE fc_id =' . $final_checking->id . ' AND final_checking_good_pelletes.deleted_at IS NULL';
            }
            
            $production = ProductionOrder::where('batch_id', '=', $request->id)->with('product', 'purchaseOrder')->latest()->first();
            $weight_unit = ProductionOrder::where('batch_id', '=', $request->id)->pluck('weight_unit');

            $data = DB::select($query);
            
            return response()->json(['shotblast' => $data, 'production' => $production, 'weight_unit' => $weight_unit]);
        }
    }
}
