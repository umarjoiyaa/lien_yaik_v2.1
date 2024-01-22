<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Machine;
use App\Models\Press;
use App\Models\ProductionOrder;
use App\Models\TemperatureMoisture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MachineController extends Controller
{
    public function index(){
        $machines = Machine::all();
        Helper::logSystemActivity('Machine', 'Machine List');
        return view('productions.machine.index', compact('machines'));
    }
    
    public function create(){
        Helper::logSystemActivity('Machine', 'Machine Create');
        return view('productions.machine.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => [
                'required',
                Rule::unique('machines', 'name')->whereNull('deleted_at'),
            ],
            'code' => [
                'required',
                Rule::unique('machines', 'code')->whereNull('deleted_at'),
            ],
        ]);

        $machine = new Machine();
        $machine->name = $request->name;
        $machine->code = $request->code;
        $machine->save();

        $TemperatureMoisture = new TemperatureMoisture();
        $TemperatureMoisture->machine_id = $machine->id;
        $TemperatureMoisture->save();

        Helper::logSystemActivity('Machine', 'Machine Create');
        return redirect()->route('machine.index')->with('custom_success', 'Machine has been Succesfully Created!');
    }

    public function edit($id){
        $machine = Machine::find($id);
        Helper::logSystemActivity('Machine', 'Machine Edit');
        return view('productions.machine.edit', compact("machine"));
    }

    public function update(Request $request, $id){
        $request->validate([
            'name' => [
                'required',
                Rule::unique('machines', 'name')->whereNull('deleted_at')->ignore($id),
            ],
            'code' => [
                'required',
                Rule::unique('machines', 'code')->whereNull('deleted_at')->ignore($id),
            ],
        ]);

        $machine = Machine::find($id);
        $machine->name = $request->name;
        $machine->code = $request->code;
        $machine->save();
        Helper::logSystemActivity('Machine', 'Machine Update');
        return redirect()->route('machine.index')->with('custom_success', 'Machine has been Succesfully Updated!');        
    }

    public function  destroy($id){
        $machine = Machine::find($id);
        $Production = ProductionOrder::where('machine_id', '=', $id)->first();
        $Press = Press::where('machine_id', '=', $id)->first();

        if($Production){
            return back()->with('custom_errors', 'This MACHINE is used in PRODUCTION ORDER!');
        }else if($Press){
            return back()->with('custom_errors', 'This MACHINE is used in PRESS!');
        }
        $machine->delete();
        Helper::logSystemActivity('Machine', 'Machine Delete');
        return redirect()->route('machine.index')->with('custom_success', 'Machine has been Succesfully Deleted!');
    }

    public function limit($id){
        $machine = Machine::with('temperature')->find($id);
        Helper::logSystemActivity('Machine', 'Machine Limit');
        return view('productions.machine.limit', compact("machine"));
    }

    public function limit_set(Request $request, $id){
        if($request->temp_low && $request->temp_high){
            $request->validate([
                'temp_low' => [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('temp_high')) {
                            $tempHigh = $request->input('temp_high');
                            if ($value >= $tempHigh) {
                                $fail('The :attribute must be lower than temp_high.');
                            }
                        }
                    },
                ],
                'temp_high' => 'numeric',
            ]);
        }
        if($request->moisture_low && $request->moisture_high){
            $request->validate([
                'moisture_low' => [
                    'required',
                    'numeric',
                    function ($attribute, $value, $fail1) use ($request) {
                        if ($request->has('moisture_high')) {
                            $moistureHigh = $request->input('moisture_high');
                            if ($value >= $moistureHigh) {
                                $fail1('The :attribute must be lower than moisture_high.');
                            }
                        }
                    },
                ],
                'moisture_high' => 'numeric',
            ]);
        }
        

        $TemperatureMoisture = TemperatureMoisture::where('machine_id','=',$id)->first();
        $TemperatureMoisture->temperature_high = $request->temp_high;
        $TemperatureMoisture->temperature_low = $request->temp_low;
        $TemperatureMoisture->moisture_high = $request->moisture_high;
        $TemperatureMoisture->moisture_low = $request->moisture_low;
        $TemperatureMoisture->save();
        Helper::logSystemActivity('Machine', 'Machine Limit Update');
        return redirect()->route('machine.index')->with('custom_success', 'Machine Limit has been Succesfully Updated!');        
    }

}
