<?php

namespace AIVIKS\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use AIVIKS\Sensor_data;
use AIVIKS\Sensor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class DataController extends Controller
{
    public function getMapData(){
        if( request()->ajax() ){
            $date_from  = $_GET['date_from'];
            $date_to    = $_GET['date_to'];
            $sensor     = $_GET['sensor'];
            $data = $this->getSensorData($date_from, $date_to, $sensor);
            return $data;
        }
    }



    public function getSensorData ($date_from, $date_to, $sensor) {
        $data = Sensor_data::select([
            'sensors.value_name AS value_name', 
            'sensors.value_max AS value_max', 
            'sensors.measuring_unit AS measuring_unit', 
         // 'sensors.max_value AS max_value',
            'sensor_data.value AS value', 
            'sensor_data.date AS date',
            'sensor_data.lat AS lat', 
            'sensor_data.long AS long',
        ])
        ->where('sensors.value_name', '=',  $sensor)
        ->whereBetween('date', [$date_from, $date_to])
        ->join('sensors', 'sensors_id', '=', 'sensors.id')
        ->get();
        return $data;
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

    
    public function downloadCSV(Request $request)
    {
        Log::debug([ $request->date_from, $request->date_to, $request->sensors]);
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename=galleries.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];

        //$list = Sensor_data::all()->toArray();

        $date_from  = $request->date_from;
        $date_to    = $request->date_to;
        $sensor     = $request->sensors;

        $list = $this->getSensorData($date_from, $date_to, $sensor)->toArray();
        # add headers for each column in the CSV download
        array_unshift($list, array_keys($list[0]));

    $callback = function() use ($list) 
        {
            $FH = fopen('php://output', 'w');
            foreach ($list as $row) { 
                fputcsv($FH, $row);
            }
            fclose($FH);
        };

        return Response::stream($callback, 200, $headers);
    }


    public function dataDownloadView()
    {        
        $sensors = Sensor::select(['measuring_unit', 'value_name'])->get()->unique([ 'value_name']);
        return view('GetDataView', ['sensors'=>$sensors]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sensors = Sensor::select(['measuring_unit', 'value_name'])->get()->unique([ 'value_name']);
        return view('ViewGraphs', ['sensors'=>$sensors]);
    }



}
