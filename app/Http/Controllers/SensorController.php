<?php

namespace AIVIKS\Http\Controllers;

use Illuminate\Http\Request;

use AIVIKS\Http\Controllers\Controller;
use AIVIKS\Devices_sensor;
use AIVIKS\Sensor;
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

    public function create ($device_id, Request $request) {
        $devices_sensor = new Devices_sensor();
        $devices_sensor->date = $request->date;
        $devices_sensor->sensors_id = $request->sensors_id;
        $devices_sensor->devices_id = $device_id;
        $devices_sensor->save();
        return redirect()->back()->with("success", "Sensorius pridėtas.");
    }

    public function destroy ($id) {
        $devices_sensor = Devices_sensor::findOrFail($id);
        $devices_sensor->delete();
        return redirect()->back()->with("success", $devices_sensor->name." Pašalintas sensorius iš prietaiso.");
    } 

    public function index($devices_id) {    //devices id. shows sensors of the device
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
        return view('devices/sensors/EditSensors', [
            'devices_sensors'=> $devices_sensors, 
            'devices_id' => $devices_id, 
            'devices_name'=>$sensors[0]->devices_name,
            'sensors' => $sensors
        ]); 
    }

    public function createView($id) {
        $sensors = Sensor::select(['id', 'name', 'value_name'])->get();
        return view('devices/sensors/create', ['sensors'=>$sensors, 'devices_id'=>$id]);
    }
}
