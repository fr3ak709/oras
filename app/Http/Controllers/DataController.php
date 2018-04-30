<?php

namespace AIVIKS\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use AIVIKS\Sensor_data;

class DataController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function get() {
    }


    public function getMapData(){
        if( request()->ajax() ){
            $date_from  = $_GET['date_from'];
            $date_to    = $_GET['date_to'];
            $sensor     = $_GET['sensor'];
        
            //$data = Sensor_data::select( [$sensor, 'lat', 'long'] )->get();
            $data = Sensor_data::select('*')
                ->whereBetween('date', [$date_from, $date_to])
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

            $data->device = 'KTU_001';

            $data->lat =  rand((int)($minLat *$mil), (int)($maxLat *$mil))/$mil;
            $data->long = rand((int)($minLong*$mil), (int)($maxLong*$mil))/$mil;
            $data->co2 =  rand((int)($minCO2 *$mil), (int)($maxCO2 *$mil))/$mil;
            $data->no2 =  rand((int)($minNO2 *$mil), (int)($maxNO2 *$mil))/$mil;
            $data->acceleration =  rand((int)($minAcceleration *$mil), (int)($maxAcceleration *$mil))/$mil;;
            $data->temperature  =  rand((int)($minTemperature *$mil), (int)($maxTemperature *$mil))/$mil;

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
