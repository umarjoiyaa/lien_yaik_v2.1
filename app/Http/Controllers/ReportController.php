<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Pellete;
use Illuminate\Http\Request;
use App\Models\WarehouseInDetail;
use App\Models\WarehouseOutDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $startDate = ($request->start_date != null) ? Carbon::parse($request->start_date)->format('Y-m-d') : Carbon::now()->subHours(24)->format('Y-m-d');
        $endDate = ($request->end_date != null) ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
        $dateIn= DB::select("select DISTINCT CAST(`date` AS DATE) AS dateonly FROM warehouse_ins where date >= '".$startDate."' And date <=  '".$endDate."'");
        $dateOut= DB::select("select DISTINCT CAST(`date` AS DATE) AS dateonly FROM warehouse_outs where date >= '".$startDate."' And date <=  '".$endDate."'");

        $totalResults = collect(
            WarehouseInDetail::join('warehouse_ins', 'warehouse_in_details.wi_id', '=', 'warehouse_ins.id')
                ->select('warehouse_ins.date', 'warehouse_in_details.pellete_id', 'warehouse_in_details.pcs', 'warehouse_in_details.weight', DB::raw('1 as status'))
                ->union(
                    WarehouseOutDetail::join('warehouse_outs', 'warehouse_out_details.wo_id', '=', 'warehouse_outs.id')
                        ->select('warehouse_outs.date', 'warehouse_out_details.pellete_id', 'warehouse_out_details.pcs', 'warehouse_out_details.weight', DB::raw('2 as status'))
                )
                ->groupBy('date', 'pellete_id', 'status', 'weight', 'pcs')
                ->orderBy('pellete_id')
                ->get()
        );

        $totalResults_pcs=[];
        foreach($totalResults as $key => $value)
        {
            $pellete = Pellete::find($value->pellete_id);
            $totalResults_pcs[$pellete->pellete_no][$value->date][$value->status]=$value->pcs;
        }

        $uniqueDates = DB::select('select date from (SELECT warehouse_ins.date AS date, warehouse_in_details.pellete_id AS pellete_id,warehouse_in_details.pcs as pcs, warehouse_in_details.weight as weight,1 as status FROM warehouse_ins JOIN warehouse_in_details ON warehouse_in_details.wi_id=warehouse_ins.id union SELECT warehouse_outs.date AS date, warehouse_out_details.pellete_id AS pellete_id,warehouse_out_details.pcs AS pcs, warehouse_out_details.weight as weight, 2 as status FROM warehouse_outs JOIN warehouse_out_details ON warehouse_out_details.wo_id=warehouse_outs.id) as query;');

        $uniqueDates_array=[];
        foreach($uniqueDates as $key => $value)
        {
            $uniqueDates_array[$key]=$value->date;
        }
        $data=[];
        $totalin = 0;
        $totalout = 0;
        
        foreach($totalResults_pcs as $pellete => $tresult)
        {
            $totalin = 0;
            $totalout = 0;
            $data[$pellete]['pellete'] = $pellete;

            foreach($dateIn as $inhead)
            {
                if(!empty($tresult[$inhead->dateonly][1]))
                {
                    $data[$pellete]['in'][$inhead->dateonly]=$tresult[$inhead->dateonly][1];
                    $totalin += $tresult[$inhead->dateonly][1];
                }
                else{
                    $data[$pellete]['in'][$inhead->dateonly]="0";
                }
            }
            $data[$pellete]['totalin'] = $totalin;

            foreach($dateOut as $outhead)
            {
                if(!empty($tresult[$outhead->dateonly][2]))
                {
                    $data[$pellete]['out'][$outhead->dateonly]=$tresult[$outhead->dateonly][2];
                    $totalout += $tresult[$outhead->dateonly][2] ;
                }
                else
                {
                    $data[$pellete]['out'][$outhead->dateonly]="0";
                }
            }
            $data[$pellete]['totalout'] = $totalout;
        }

        Helper::logSystemActivity('Report', 'Generate Report');
        return view('reports.report.index', compact('totalResults_pcs', 'dateIn', 'dateOut', 'data', 'uniqueDates','startDate','endDate'));
    }

}
