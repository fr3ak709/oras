<?php

namespace AIVIKS\Http\Controllers;

use Illuminate\Http\Request;
use AIVIKS\Device;
use AIVIKS\Devices_sensor;
use AIVIKS\Sensor;
use Illuminate\Support\Facades\DB;

class DeviceController extends Controller
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
        if (empty($request->name)) {
            return redirect()->back()->with("error","Užpildykitę pavadinimo lauką.  ". 
                                        "Pavadinimas: ".'"'.$request->name.'"  ');
        }

        $device = new Device();
        $device->name = $request->name;
        $device->users_id = 1;
        $device->save();
        return redirect()->back()->with("success", "Prietaisas pridėtas.");
    } 

    public function destroyDevice() {
        $device = Device::findOrFail($id);
        $device->delete();
        return redirect()->back()->with("success", $device->name." Prietaisas buvo ištrintas sėkmingai.");
    }

    public function index () {
        /*
        SELECT devices.id as devices_id, devices.name, sensors.id as devices_id, sensors.name
        FROM sensors, devices_sensors, devices
        WHERE  devices_sensors.devices_id = devices.id
              AND devices_sensors.sensors_id = sensors.id  
        */

        $devices = Device::all();
        foreach($devices as $device){
            $device->sensors = Devices_sensor::select('*')->where('devices_id', '=', $device->id)
            	->join('sensors', 'sensors_id', '=', 'sensors.id')->get();
            foreach($device->sensors as $sensor) {
                $ammount_of_days =  floor(365 * $sensor->expected_operating_time );
                $sensor->valid_till = date('Y-m-d', strtotime('+'. $ammount_of_days .' days', strtotime($sensor->date)) ); 
                $sensor->needs_replacing = ( $sensor->valid_till < date("Y-m-d") );
            }
        }
        return view('devices/EditDevices', ['devices'=>$devices]);
    } 

}
