<?php

namespace AIVIKS\Http\Controllers;

use Illuminate\Http\Request;

use AIVIKS\Http\Controllers\Controller;
use AIVIKS\Devices_sensor;
use AIVIKS\Sensor;
use AIVIKS\Sensor_data;
use Illuminate\Support\Facades\Auth;


class SensorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('amAdmin');
    }

    public function create (Request $request) {
        try{
            $sensor = new Sensor();
            /* $sensor->precision = $request->precision;
            $sensor->power_consumption = $request->power_consumption;
            $sensor->voltage_min = $request->voltage_min;
            $sensor->voltage_max = $request->voltage_max;
            $sensor->operating_temperature_min = $request->operating_temperature_min;
            $sensor->operating_temperature_max = $request->operating_temperature_max; */
            $sensor->name = $request->name;
            $sensor->value_name = $request->value_name;
            $sensor->measuring_unit = $request->measuring_unit;
            $sensor->expected_operating_time = floatval($request->expected_operating_time);
            $sensor->value_max = floatval($request->value_max);
            $sensor->save();
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return redirect()->back()->with("success",  $sensor->name." Sensorius pridėtas.");
    }   
    public function update (Request $request) {
        try{
            $sensor = Sensor::find($request->id);
            /* $sensor->precision = $request->precision;
            $sensor->power_consumption = $request->power_consumption;
            $sensor->voltage_min = $request->voltage_min;
            $sensor->voltage_max = $request->voltage_max;
            $sensor->operating_temperature_min = $request->operating_temperature_min;
            $sensor->operating_temperature_max = $request->operating_temperature_max; */
            $sensor->name = $request->name;
            $sensor->value_name = $request->value_name;
            $sensor->measuring_unit = $request->measuring_unit;
            $sensor->expected_operating_time = floatval($request->expected_operating_time);
            $sensor->value_max = floatval($request->value_max);
            $sensor->save();
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return redirect()->back()->with("success",  $sensor->name." Sensorius atnaujintas.");
    }

    public function addSensorToDevice ($device_id, Request $request) {
        try{        
            $devices_sensor = new Devices_sensor();
            $devices_sensor->date = $request->date;
            $devices_sensor->sensors_id = $request->sensors_id;
            $devices_sensor->devices_id = $device_id;
            $devices_sensor->save();  
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return redirect()->back()->with("success", "Sensorius pridėtas.");
    }

    public function removeFromDevice ($id) {
        $devices_sensor = Devices_sensor::findOrFail($id);
        try{
            $devices_sensor->delete();  
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return redirect()->back()->with("success", $devices_sensor->name." Pašalintas sensorius iš prietaiso.");
    } 
    public function destroy ($id) {
        try{
            $sensor = Sensor::findOrFail($id);
            $sensor->delete();    
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return redirect()->back()->with("success", $sensor->name." Pašalintas sensorius.");
    } 
    public function devicesSensorsView($devices_id) {    //shows sensors that are added to the device
              
        try{
            $sensors = Sensor::select(['id', 'name', 'value_name'])->get();
            $devices_sensors = Devices_sensor::select([
                    'devices_sensors.id AS id', 
                    'devices_sensors.date AS date', 
                    'sensors.expected_operating_time AS expected_operating_time', 
                    'sensors.name AS name',
                    'sensors.value_name AS value_name',
                    'devices.name AS devices_name'
            ])->where('devices_id', '=', $devices_id)
            ->join('sensors', 'sensors.id', '=', 'sensors_id')
            ->join('devices', 'devices.id', '=', 'devices_id')
            ->get();
            foreach($devices_sensors as $devices_sensor ) { //check if sensor needs replacing
                $ammount_of_days =  floor(365 * $devices_sensor->expected_operating_time );
                $devices_sensor->valid_till = date('Y-m-d', strtotime('+'. $ammount_of_days .' days', strtotime($devices_sensor->date)) ); 
                $devices_sensor->needs_replacing = ( $devices_sensor->valid_till < date("Y-m-d") );
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return view('devices/sensors/EditDevicesSensors', [
            'devices_sensors'=> $devices_sensors, 
            'devices_id' => $devices_id, 
            'devices_name'=>$sensors[0]->devices_name,
            'sensors' => $sensors
        ]); 
    }

    public function sensorsView() {      
        try{
            $sensors = Sensor::select(
                [
                    'sensors.id', 
                    'sensors.name', 
                    'sensors.value_name',
                    'sensors.value_max', 
                    'sensors.measuring_unit', 
                    'sensors.expected_operating_time',
                    'sensors.created_at'
                ]
            )
            ->get();
            foreach ($sensors as $sensor) {
                $sensor->ammount_of_data = Sensor_data::where('sensors_id', '=', $sensor->id)
                ->count();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with("error",  $e->getMessage()." Įvyko klaida.");
        }
        return view('sensors/EditSensors', ['sensors'=>$sensors]);
    }

}
