<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Pellete;
use App\Models\Product;
use App\Models\ProductionOrder;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckPelleteController extends Controller
{
    function index()
    {
        if (!Auth::user()->hasPermissionTo('Check Pellete')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        Helper::logSystemActivity('Check Pellete', 'View Check Pellete');
        return view('warehouses.check-pellete.index');
    }

    function check(Request $request)
    {
        $check = $request->search;
        $pellete = Pellete::where('pellete_no', '=', $check)->with('batches')->first();
        Helper::logSystemActivity('Check Pellete', 'Search Check Pellete');

        if ($pellete && $pellete->batches) {
            $batch = $pellete->batches;

            $latestTableName = null;
            $latestTimestamp = null;

            $tables = ['grindings', 'drillings', 'final_checkings'];

            foreach ($tables as $table) {
                $latestRecord = DB::table($table)
                    ->select('date', 'batch_id')
                    ->where('batch_id', $batch->id)
                    ->orderByDesc('date')
                    ->first();

                if ($latestRecord && (!$latestTimestamp || $latestRecord->date > $latestTimestamp)) {
                    $latestTableName = $table;
                    $latestTimestamp = $latestRecord->date;
                }
            }

            $production = ProductionOrder::where('batch_id', $pellete->batch)->latest()->first();

            if ($production) {
                $purchase = PurchaseOrder::find($production->order_id);
                $product = Product::find($production->product_id);
            } else {
                $purchase = null;
                $product = null;
            }

            return response()->json([
                "pellete" => $pellete,
                "batch" => $batch,
                "product" => $product,
                "purchase" => $purchase,
                "latestRecord" => $latestTableName,
            ]);
        } else {
            return response()->json('Not Get');
        }
    }
}
