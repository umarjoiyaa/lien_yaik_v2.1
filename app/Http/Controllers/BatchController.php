<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BatchController extends Controller
{
    public function index(){
        if(
            Auth::user()->hasPermissionTo('Batch List') ||
            Auth::user()->hasPermissionTo('Batch Create') ||
            Auth::user()->hasPermissionTo('Batch Edit') ||
            Auth::user()->hasPermissionTo('Batch Delete')
        ){
            $batches = Batch::all();
            Helper::logSystemActivity('Batch', 'Batch List');
            return view('productions.batch.index', compact('batches'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Batch Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        Helper::logSystemActivity('Batch', 'Batch Create');
        return view('productions.batch.create');
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Batch Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            'batch_no' => [
                'required',
                Rule::unique('batches', 'batch_no')->whereNull('deleted_at'),
            ],
            "planned_start" => "required",
            "planned_end" => "required"
        ]);

        $batch = new Batch();
        $batch->batch_no = $request->batch_no;
        $batch->planned_start = Carbon::parse($request->planned_start);
        $batch->planned_end = Carbon::parse($request->planned_end);
        $batch->duration = Carbon::parse($request->planned_end)->diffInHours(Carbon::parse($request->planned_start));
        $batch->save();
        
        Helper::logSystemActivity('Batch', 'Batch Store');
        return redirect()->route('batch.index')->with('custom_success', 'Batch has been Succesfully Created!');    }

    public function edit($id){
        if(!Auth::user()->hasPermissionTo('Batch Edit')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        $batch = Batch::find($id);
        Helper::logSystemActivity('Batch', 'Batch Edit');
        return view('productions.batch.edit', compact('batch'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Batch Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $request->validate([
            'batch_no' => [
                'required',
                Rule::unique('batches', 'batch_no')->whereNull('deleted_at')->ignore($id),
            ],
            "planned_start" => "required",
            "planned_end" => "required"
        ]);

        $batch = Batch::find($id);
        $batch->batch_no = $request->batch_no;
        $batch->planned_start = Carbon::parse($request->planned_start);
        $batch->planned_end = Carbon::parse($request->planned_end);
        $batch->duration = Carbon::parse($request->planned_end)->diffInHours(Carbon::parse($request->planned_start));
        $batch->save();

        Helper::logSystemActivity('Batch', 'Batch Update');
        return redirect()->route('batch.index')->with('custom_success', 'Batch has been Succesfully Updated!');
    }

    public function destroy($id){
        if(!Auth::user()->hasPermissionTo('Batch Delete')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $batch = Batch::find($id);
        $batch->delete();

        Helper::logSystemActivity('Batch', 'Batch Delete');
        return redirect()->route('batch.index')->with('custom_success', 'Batch has been Succesfully Deleted!');
    }

}
