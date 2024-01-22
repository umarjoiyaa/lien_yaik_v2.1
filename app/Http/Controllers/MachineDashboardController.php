<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Machine;
use App\Helpers\Helper;
use App\Models\PressDetail;
use Illuminate\Http\Request;
use App\Models\MachineApiSum;
use App\Models\ProductionOrder;
use App\Models\TemperatureMoisture;
use Illuminate\Support\Facades\Auth;
use App\Models\TemperatureMoistureApi;

class MachineDashboardController extends Controller
{
    public function index()
    {

        if (!Auth::user()->hasPermissionTo('Machine Dashboard')) {
            return back()->with('custom_errors', 'You don`t have Right Permission');
        }
        
        Helper::logSystemActivity('Machine Dashboard', 'View Machine Dashboard');
        return view('dashboard.machine.index');
    }

    function get(Request $request)
    {
        $machine_data = array();
        $machines = Machine::select('id', 'name')->get();
        foreach ($machines as $machine) {
            
            $current_temp = TemperatureMoistureApi::where('machine_id', '=', $machine->id)->whereNull('end_time')->orderBy('id', 'DESC')->first();
            $set_temp = TemperatureMoisture::where('machine_id', '=', $machine->id)->first();

            if(empty($current_temp )){
                $machine_data[$machine->name]['temp'] = 0;
                $machine_data[$machine->name]['moisture'] = 0;

            }else{
                $machine_data[$machine->name]['temp'] = $current_temp->temperature;
                $machine_data[$machine->name]['moisture'] = $current_temp->moisture;
                
                if(isset($set_temp)){
                    if(!empty($set_temp->temp_high || $set_temp->temp_low)){

                        if($current_temp->temperature > $set_temp->temp_high){

                            $machine_data[$machine->name]['check_temp'] = "Temperature High";

                        }else if($current_temp->temperature < $set_temp->temp_low){

                            $machine_data[$machine->name]['check_temp'] = "Temperature Low";

                        }

                    }
                }else{
                    $machine_data[$machine->name]['check_temp'] = "";
                }
                
                if(isset($set_temp)){

                    if(!empty($set_temp->moisture_high || $set_temp->moisture_low)){

                        if($current_temp->moisture > $set_temp->moisture_high){

                            $machine_data[$machine->name]['check_moisture'] = "Moisture High";

                        }else if($current_temp->moisture < $set_temp->moisture_low){

                            $machine_data[$machine->name]['check_moisture'] = "Moisture Low";

                        }

                    }
                }else{
                    $machine_data[$machine->name]['check_temp'] = "";
                }
            }

            $press = PressDetail::WhereNull('end_time')->where('machine_id', '=', $machine->id)->latest('start_time')->first();
            
            if(empty($press)){
                $machine_data[$machine->name]['batch'] = null;
                $machine_data[$machine->name]['status'] = "Stopped";
                $machine_data[$machine->name]['target'] = 0;
                $machine_data[$machine->name]['current'] = 0;
                $machine_data[$machine->name]['planned'] = 0;
                $machine_data[$machine->name]['mold'] = 0;

            }else{

                $sumCavityTotal = MachineApiSum::where('machine_id', '=', $machine->id)->where('batch_id', '=', $press->batch_id)->sum('sum_cavity');
                
                $batch = Batch::find($press->batch_id);
                $machine_data[$machine->name]['batch'] = $batch->batch_no;
                $machine_data[$machine->name]['status'] = "On Going";


                $production = ProductionOrder::where('batch_id', '=', $batch->id)->latest()->first();
                if(empty($production)){
                    $machine_data[$machine->name]['target'] = 0;
                    $machine_data[$machine->name]['current'] = 0;
                    $machine_data[$machine->name]['planned'] = 0;
                }else{
                    $machine_data[$machine->name]['target'] = $production->target_need;
                    $machine_data[$machine->name]['current'] = $production->target_produce;
                    $machine_data[$machine->name]['planned'] = $production->mold_shot;
                    if(!empty($sumCavityTotal)){
                        $machine_data[$machine->name]['mold'] = $sumCavityTotal / $production->no_cavity;
                    }else{
                        $machine_data[$machine->name]['mold'] = 0;
                    }
                }

            }
            
        }
       
        return response()->json($machine_data);
    }
}
