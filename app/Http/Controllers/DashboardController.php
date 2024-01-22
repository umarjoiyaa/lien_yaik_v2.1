<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MachineApiSum;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {

        if (!Auth::user()->hasPermissionTo('Dashboard')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        Helper::logSystemActivity('Dashboard', 'View Dashboard');
        return view('dashboard.main.index');
    }

    function get(Request $request)
    {
        $startDate = $request->startDate ?? Carbon::now()->subWeeks(1)->format('Y-m-d H:i:s');
        $endDate = $request->endDate ?? Carbon::now()->addDay()->format('Y-m-d H:i:s');

        $results = MachineApiSum::with(['batch.finalChecking', 'batch.warehouseIn', 'batch.productionOrder.purchaseOrder'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $output = '';

        foreach ($results as $result) {
            $date = $result->created_at->format('Y-m-d');
            $batch = $result->batch;
            $final = $batch->finalChecking;
            $warehouse = $batch->warehouseIn;
            $customer = $batch->productionOrder;
            $purchase = $customer->purchaseOrder ?? null;

            $batches = $batch->batch_no;
            $total_cavity = $result->sum_cavity;
            $weight = $final ? $final->total_good_weight : "0";
            $pcs = $final ? $final->total_good_pcs : "0";
            $warehousepcs = $warehouse ? $warehouse->total_pcs : "0";
            $name = $purchase ? $purchase->customer_name : "";

            $output .= '<tr><td>' . $date . '</td><td>' . $batches . '</td><td>' . $name . '</td><td>' . $total_cavity . '</td><td>' . $weight . '</td><td>' . $pcs . '</td><td>' . $warehousepcs . '</td></tr>';
        }
        Helper::logSystemActivity('Dashboard', 'Generate Dashboard');
        return response()->json($output);
    }
}
