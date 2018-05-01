<?php

namespace AIVIKS\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use AIVIKS\Sensor_data;

class DataController extends Controller
{
    public function getMapData(){
        if( request()->ajax() ){
            $date_from  = $_GET['date_from'];
            $date_to    = $_GET['date_to'];
            $sensor     = $_GET['sensor'];
        
            $data = Sensor_data::select([
                    'sensors.value_name AS value_name', 
                 // 'sensors.max_value AS max_value',
                    'sensor_data.value AS value', 
                    'sensor_data.date AS date',
                    'sensor_data.lat AS lat', 
                    'sensor_data.long AS long',
                ])->where('value_name', '=',  $sensor)
                ->whereBetween('date', [$date_from, $date_to])
                ->join('sensors', 'sensors_id', '=', 'sensors.id')
                ->get();
        return $data;
        }
    }
    
    public function generate () {
        $date = date('Y-m-d H:i:s');
        $minLat  = 54.82; $maxLat  = 54.96;
        $minLong = 23.76; $maxLong = 24.10;
        $mil = 1000000;
        $minCO2 = 20.0; $maxCO2 = 200.0; 
        $minNO2 = 20.0; $maxNO2 = 200.0; 
        $minTemperature = 15.0; $maxTemperature = 30.0; 
        $minAcceleration = 0.0; $maxAcceleration = 10.0; 
        for ($i = 0; $i < 100; $i++) {
            $data = new Sensor_data;
            $date = date('Y-m-d H:i:s',strtotime('+1 second',strtotime($date)));
            $data->date = $date;
            $data->lat =  rand((int)($minLat *$mil), (int)($maxLat *$mil))/$mil;
            $data->long = rand((int)($minLong*$mil), (int)($maxLong*$mil))/$mil;
            $data->value =  rand((int)($minCO2 *$mil), (int)($maxCO2 *$mil))/$mil;
            $data->sensors_id = 100;

            $data->save();
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ViewGraphs');
    }



}
