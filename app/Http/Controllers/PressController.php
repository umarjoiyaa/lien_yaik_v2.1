<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Batch;
use App\Models\Machine;
use App\Models\Press;
use App\Models\PressDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PressController extends Controller
{
    public function index(){
        if(
            Auth::user()->hasPermissionTo('Press List') ||
            Auth::user()->hasPermissionTo('Press Create') ||
            Auth::user()->hasPermissionTo('Press Edit') ||
            Auth::user()->hasPermissionTo('Press Delete')
        ){
            $presses = Press::all();
            Helper::logSystemActivity('Press', 'Press List');
            return view('productions.press.index', compact('presses'));
        }
        return back()->with('custom_errors', 'You don`t have Right Permission');
    }

    public function create(){
        if (!Auth::user()->hasPermissionTo('Press Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $users = User::all();
        $batches = Batch::all();
        $machines = Machine::all();

        Helper::logSystemActivity('Press', 'Press Create');
        return view('productions.press.create', compact('users', 'batches', 'machines'));
    }

    public function store(Request $request){
        if (!Auth::user()->hasPermissionTo('Press Create')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $request->validate([
            "pic" => "required",
            "batch" => "required",
            "machine" => "required",
            "date" => "required"
        ]);

        $press = new Press();
        $press->pic = $request->pic;
        $press->batch_id = $request->batch;
        $press->machine_id = $request->machine;
        $press->date = $request->date;
        $press->save();
        
        Helper::logSystemActivity('Press', 'Press Store');
        return redirect()->route('press.index')->with('custom_success', 'Press has been Succesfully Created!');    }

    public function edit($id){
        if(!Auth::user()->hasPermissionTo('Press Edit')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }

        $press = Press::find($id);
        $users = User::all();
        $batches = Batch::all();
        $machines = Machine::all();
        $already = PressDetail::where('press_id', '=', $id)->count();
        $check_machines = PressDetail::where('batch_id', '=', $press->batch_id)->where('machine_id', '=', $press->machine_id)->where('press_id',  '=', $press->id)->orderby('id', 'DESC')->first();

        Helper::logSystemActivity('Press', 'Press Edit');
        return view('productions.press.edit', compact('press', 'users', 'batches', 'machines', 'already', 'check_machines'));
    }

    public function update(Request $request, $id){
        if (!Auth::user()->hasPermissionTo('Press Edit')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $request->validate([
            "pic" => "required",
            "batch" => "required",
            "machine" => "required",
            "date" => "required"
        ]);

        $press = Press::find($id);
        $press->pic = $request->pic;
        $press->batch_id = $request->batch;
        $press->machine_id = $request->machine;
        $press->date = $request->date;
        $press->save();

        Helper::logSystemActivity('Press', 'Press Update');
        return redirect()->route('batch.index')->with('custom_success', 'Press has been Succesfully Updated!');
    }

    public function destroy($id){
        if(!Auth::user()->hasPermissionTo('Press Delete')){
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        $batch = Press::find($id);
        PressDetail::where('press_id', '=', $id)->delete();
        $batch->delete();

        Helper::logSystemActivity('Press', 'Press Delete');
        return redirect()->route('batch.index')->with('custom_success', 'Press has been Succesfully Deleted!');
    }

    public function start(Request $request)
    {
        $ismachinestart = null;
        
        $JustSelected = Press::where('id', $request->press)->where('batch_id','=', $request->batch_id)->where('machine_id' ,'=' , $request->machine_id)->orderby('id', 'DESC')->first();
        
        if(!empty($JustSelected)){
            $ismachinestart = PressDetail::where('end_time', '=', null)->where('batch_id', '!=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id', '!=', $request->press_id)->orderby('id', 'DESC')->first();
        }
        
        $alreadyexist = PressDetail::where('status', '=', 1)->where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id', '=', $request->press_id)->orderby('id', 'DESC')->first();
        $alreadypaused = PressDetail::where('status', '=', 1)->where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id', '=', $request->press_id)->orderby('id', 'DESC')->first();
        $stopped = PressDetail::where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id', '=', $request->press_id)->where('status', '=', 3)->first();
                       
        if (!$ismachinestart) {
            
            if ($request->status == 1 && !$alreadyexist && !$stopped) {
                
                PressDetail::create([
                    'batch_id' => $request->batch_id,
                    'machine_id' => $request->machine_id,
                    'press_id' => $request->press_id,
                    'status' => $request->status,
                    'start_time' => Carbon::now('Asia/Kuching')
                ]);
                $check_machine = PressDetail::where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id',  '=', $request->press_id)->orderby('id', 'DESC')->first();
                return response()->json([
                    'message' => 'Machine Started ' . Carbon::now('Asia/Kuching'),
                    'check_machine' => $check_machine
                ]);
            } else if ($request->status == 2 && $alreadypaused && !$stopped) {

                $mpo = PressDetail::where('batch_id', $request->batch_id)->where('machine_id', $request->machine_id)->where('press_id', $request->press_id)->where('end_time', '=', null)->orderby('id', 'DESC')->first();
                $mpo->status = $request->status;
                $mpo->end_time = Carbon::now('Asia/Kuching');
                $mpo->save();
                $check_machine = PressDetail::where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id',  '=', $request->press_id)->orderby('id', 'DESC')->first();
                return response()->json([
                    'message' => 'Machine Paused ' . Carbon::now('Asia/Kuching'),
                    'check_machine' => $check_machine
                ]);
            } else if ($request->status == 3 && !$stopped) {
                $mpo = PressDetail::where('batch_id', $request->batch_id)->where('machine_id', $request->machine_id)->where('press_id', $request->press_id)->orderby('id', 'DESC')->first();
                if(!empty($mpo)){
                    $mpo->status = $request->status;
                    $mpo->end_time = Carbon::now('Asia/Kuching');
                    $mpo->save();
                    $check_machine = PressDetail::where('batch_id', '=', $request->batch_id)->where('machine_id', '=', $request->machine_id)->where('press_id',  '=', $request->press_id)->orderby('id', 'DESC')->first();
                    return response()->json([
                        'message' => 'Machine Stopped ' . Carbon::now('Asia/Kuching'),
                        'check_machine' => $check_machine
                    ]);
                }else{
                    return response()->json([
                        'message' => 'Your are in wrong Press'
                    ]);
                }
            }
        } else {
            return response()->json([
                'message' => 'Other Batch with same work center is RUNNING ,CAN NOT START'
            ]);
        }
    }
}
