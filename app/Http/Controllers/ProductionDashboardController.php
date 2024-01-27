<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\TemperatureMoistureApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductionDashboardController extends Controller
{
    public function index()
    {

        if (!Auth::user()->hasPermissionTo('Production Dashboard')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        Helper::logSystemActivity('Production Dashboard', 'View Production Dashboard');
        return view('dashboard.production.index');
    }

    function get(Request $request)
    {
        $startDate = ($request->startDate != null) ? $request->startDate : Carbon::now()->subWeeks('1')->format('Y-m-d H:i:s');
        $endDate = ($request->endDate != null) ? $request->endDate : Carbon::now()->addDay()->format('Y-m-d H:i:s');

        $date = array();
        $temperature = TemperatureMoistureApi::whereBetween('created_at', [$startDate,$endDate])->get();

        foreach($temperature as $temp){
           $date[] = $temp->created_at->format('d-m-Y');
        }

        Helper::logSystemActivity('Production Dashboard', 'Generate Production Dashboard');
        return response()->json(['temp' => $temperature, 'dates' => $date]);
    }
}
