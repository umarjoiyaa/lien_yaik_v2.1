<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Pellete;
use App\Models\WarehouseIn;
use App\Models\WarehouseOut;
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
        $endDate = ($request->start_date != null) ? Carbon::parse($request->end_date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');

        $dateIn = WarehouseIn::whereBetween('date', [$startDate, $endDate])->distinct()->pluck('date');
        $dateOut = WarehouseOut::whereBetween('date', [$startDate, $endDate])->distinct()->pluck('date');

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
            $pellete = Pellete::find($value->pellete);
            $totalResults_pcs[$pellete->pellete_no][$value->date][$value->status]=$value->pcs;
        }

        $uniqueDates = $totalResults->pluck('date')->unique();

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
        return view('reports.report.index', compact('totalResults_pcs', 'dateIn', 'dateOut', 'data', 'uniqueDates'));
    }

}
