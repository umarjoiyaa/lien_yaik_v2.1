<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Machine;
use App\Models\MachineApi;
use App\Models\PressDetail;
use Illuminate\Http\Request;
use App\Models\MachineApiSum;
use App\Mail\MoistureLowEmail;
use App\Mail\MoistureHighEmail;
use Illuminate\Support\Facades\DB;
use App\Models\TemperatureMoisture;
use Illuminate\Support\Facades\Mail;
use App\Models\TemperatureMoistureApi;

class APIController extends Controller
{
    // MACHINE COUNT 
    public function GetMachine(Request $req)
    {
        try {
                        
            $responses = json_decode($req->getContent());
            $machine_id = $responses->data->machine_id;
            $machine_value = $responses->data->machine_value;

            if (empty($machine_id)) {
                return response()->json("Data Format Is Not Correct");
            }
            $CheckMachineStart = PressDetail::where('status', '=', 1)->where('machine_id', '=', $machine_id)->first();

            if(empty($CheckMachineStart)){
                return response()->json('Machine Not Started');
            }
            $cavities = DB::select('SELECT purchase_orders.cavities FROM batches INNER JOIN production_orders ON batches.id = production_orders.batch_id INNER JOIN purchase_orders ON production_orders.order_id = purchase_orders.id where batches.id = '.$CheckMachineStart->batch_id);

            if(empty($cavities)){
                return response()->json('Cavity Not Set');
            }else{
                $data = new MachineApi;
                $data->machine_id=$machine_id;
                $data->batch_id=$CheckMachineStart->batch_id;
                $data->press_id=$CheckMachineStart->press_id;
                $data->drop_time=Carbon::parse('Asia/Kuching')->format('Y-m-d H:i:s');
                $data->cavity=$cavities[0]->cavities;
                $data->save();
            }
            $CheckMachineSum = MachineApiSum::where('machine_id', '=',$machine_id)->where('date','=',Carbon::parse('Asia/Kuching')->format('Y-m-d'))->where('press_id', '=', $CheckMachineStart->press_id)->first();

            if(empty($CheckMachineSum)){                    
                MachineApiSum::create([
                    'machine_id' => $machine_id,
                    'batch_id' => $CheckMachineStart->batch_id,
                    'press_id' => $CheckMachineStart->press_id,
                    'date' => Carbon::parse('Asia/Kuching')->format('Y-m-d'),
                    'sum_cavity' => $cavities[0]->cavities,
                ]);
            }else{
                $data= MachineApiSum::find($CheckMachineSum->id);
                $data->sum_cavity=$cavities[0]->cavities + $CheckMachineSum->sum_cavity;
                $data->save();
            }
                                
            return response()->json(["status"=>'200',"data"=>$data]) ;

        } catch (\Exception $th) {
            return $th->getMessage();
        }

    }

    //MACHINE LIMIT
    public function MachineLimit(Request $req)
    { 
        try {
            $responses = json_decode($req['data']);
            
            foreach($responses as $response){
                $machine_ids = $response->machine_id ?? null;
                $machine_temperature = $response->temperature ?? null;
                $machine_moisture = $response->moisture ?? null;
                $machine_time = Carbon::parse($response->date_time) ?? null;
                if (empty($machine_ids)) {
                    return response()->json("Data Format Is Not Correct");
                } 
                $machine = Machine::find($machine_ids);
                $temp_moisture = TemperatureMoisture::where('machine_id','=',$machine_ids)->first();
        
                $CheckTemperature = TemperatureMoistureApi::where('machine_id', '=',$machine_ids)->orderBy('id','Desc')->first();
                if(!empty($CheckTemperature)){                    
                    $CheckTemperature->end_time = $machine_time;
                    $CheckTemperature->save();

                    TemperatureMoistureApi::create([
                        'machine_id' => $machine_ids,
                        'start_time' => $machine_time,
                        'temperature' => $machine_temperature,
                        'moisture' => $machine_moisture
                    ]);
                        
                    if(!empty($temp_moisture)){

                        if(!empty($temp_moisture->moisture_high || $temp_moisture->moisture_low)){
    
                            if($machine_moisture > $temp_moisture->moisture_high){
    
                                $user = User::first();
    
                                $email = new MoistureHighEmail($user, $machine->name, $temp_moisture->moisture_high);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Moisture  Have Cross The High Limits ('.$temp_moisture->moisture_high.')']);
    
                            }else if($machine_moisture < $temp_moisture->moisture_low){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->moisture_low);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Moisture  Have Cross The Low Limits ('.$temp_moisture->moisture_low.')']) ;
    
                            }
    
                        }else{}
    
                        if(!empty($temp_moisture->temp_high || $temp_moisture->temp_low)){
    
                            if($machine_temperature > $temp_moisture->temp_high){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->temp_high);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Temperature  Have Cross The High Limits ('.$temp_moisture->temp_high.')']) ;
    
                            }else if($machine_temperature < $temp_moisture->temp_low){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->temp_low);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Temperature  Have Cross The Low Limits ('.$temp_moisture->temp_low.')']) ;
    
                            }
    
                        }else{}
                    }

                    return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' current Temperature ('.$machine_temperature.') And Moisture ('.$machine_moisture.')']);

                }else{
                    TemperatureMoistureApi::create([
                        'machine_id' => $machine_ids,
                        'start_time' => $machine_time,
                        'temperature' => $machine_temperature,
                        'moisture' => $machine_moisture
                    ]);

                    if(!empty($temp_moisture)){

                        if(!empty($temp_moisture->moisture_high || $temp_moisture->moisture_low)){
    
                            if($machine_moisture > $temp_moisture->moisture_high){
    
                                $user = User::first();
    
                                $email = new MoistureHighEmail($user, $machine->name, $temp_moisture->moisture_high);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Moisture  Have Cross The High Limits ('.$temp_moisture->moisture_high.')']);
    
                            }else if($machine_moisture < $temp_moisture->moisture_low){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->moisture_low);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Moisture  Have Cross The Low Limits ('.$temp_moisture->moisture_low.')']) ;
    
                            }
    
                        }else{}
    
                        if(!empty($temp_moisture->temp_high || $temp_moisture->temp_low)){
    
                            if($machine_temperature > $temp_moisture->temp_high){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->temp_high);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Temperature  Have Cross The High Limits ('.$temp_moisture->temp_high.')']) ;
    
                            }else if($machine_temperature < $temp_moisture->temp_low){
    
                                $user = User::first();
    
                                $email = new MoistureLowEmail($user, $machine->name, $temp_moisture->temp_low);
                                Mail::to($user->email)->send($email);
                                return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' Temperature  Have Cross The Low Limits ('.$temp_moisture->temp_low.')']) ;
    
                            }
    
                        }else{}
                    }
                    
                    return response()->json(["status"=>'200',"msg"=>'Your Machine '.$machine->name.' current Temperature ('.$machine_temperature.') And Moisture ('.$machine_moisture.')']);

                }

            }

        } catch (\Exception $th) {
            return $th->getMessage();
        }
    }
}
