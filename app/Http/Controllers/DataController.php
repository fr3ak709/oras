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
    public function getData(){
        if( request()->ajax() ){
            $date_from  = $_GET['date_from'];
            $date_to    = $_GET['date_to'];
            $sensor     = $_GET['sensor'];
            $data = $this->fetchSensorData($date_from, $date_to, $sensor);
            return $data;
        }
    }

    public function test() {
        $date = date("Y-m-d");
        $data = ['string' , 'array' ];
        $sensors = Sensor::select( DB::raw (
            'sensors.value_name AS value_name, 
             sensors.value_max  AS value_max, 
             sensors.measuring_unit AS measuring_unit' ) )
        ->groupBy('value_name')
        ->get();
        
        $data = [ ];
        foreach($sensors as $sensor)
            array_push ( 
                $data ,
                Sensor_data::select(
                    DB::raw('
                        AVG(sensor_data.value) AS value,  
                        HOUR(sensor_data.date) AS hour' 
                    )
                )
                ->where('value_name', '=', $sensor->value_name)
                ->whereBetween('date', [
                    date('Y-m-d',strtotime('+1 days',strtotime($date ))),  
                    date('Y-m-d',strtotime('+3 days',strtotime($date )))
                ])
                ->join('sensors', 'sensors_id', '=', 'sensors.id')
                ->groupBy(DB::raw('hour'))
                ->orderBy('date')
                ->get()
            );  
        //echo $data[0][1]->value;
         echo $data[0];
    }

    public function getAvgData(){
        if( request()->ajax() ){
            $date_from  = $_GET['date_from'];
            $date_to    = $_GET['date_to'];
            $sensor     = $_GET['sensor'];
            $data = $this->fetchAvgSensorData($date_from, $date_to, $sensor);
            return $data;
        }
    }


    public function fetchSensorData ($date_from, $date_to, $sensor) {
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
    public function fetchAvgSensorData ($date_from, $date_to, $sensor) {
        
        $date = date('Y-m-d H:i:s');
        $data = Sensor_data::select(
            DB::raw('
                sensors.value_name AS value_name, 
                sensors.value_max AS value_max, 
                sensors.measuring_unit AS measuring_unit,
                AVG(sensor_data.value) AS value,  
                DATE(sensor_data.date) AS date' 
            )
        )
        ->where('value_name', '=', $sensor)
        ->whereBetween('date', [
            $date_from,  
            $date_to
        ])
        ->join('sensors', 'sensors_id', '=', 'sensors.id')
        ->groupBy(DB::raw('DATE(date)'))
        ->orderBy('date')
        ->get();  

        return $data;
    }
    
    public function generate () {
        $startdate = date('Y-m-d H:i:s');
        $startdate = date('Y-m-d H:i:s',strtotime('+2 days',strtotime($startdate)));
        $minLat  = 54.82; $maxLat  = 54.96;
        $minLong = 23.76; $maxLong = 24.10;
        $mil = 1000000;
        $minCO2 = 20.0; $maxCO2 = 200.0; 
        $minNO2 = 20.0; $maxNO2 = 200.0; 
        $minTemperature = 15.0; $maxTemperature = 30.0; 
        $minAcceleration = 0.0; $maxAcceleration = 10.0; 
        $date = $startdate;
        for ($i = 0; $i < 100; $i++) {
            $data = new Sensor_data;
            $date = date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime($date)));
            $data->date = $date;
            $data->lat =  rand((int)($minLat *$mil), (int)($maxLat *$mil))/$mil;
            $data->long = rand((int)($minLong*$mil), (int)($maxLong*$mil))/$mil;
            $data->value =  rand((int)($minCO2 *$mil), (int)($maxCO2 *$mil))/$mil;
            $data->sensors_id = 100;
            $data->save();
        }
        $date = $startdate;
        for ($i = 0; $i < 100; $i++) {
            $data = new Sensor_data;
            $date = date('Y-m-d H:i:s',strtotime('+10 minutes',strtotime($date)));
            $data->date = $date;
            $data->lat =  rand((int)($minLat *$mil), (int)($maxLat *$mil))/$mil;
            $data->long = rand((int)($minLong*$mil), (int)($maxLong*$mil))/$mil;
            $data->value =  rand((int)($minCO2 *$mil), (int)($maxCO2 *$mil))/$mil;
            $data->sensors_id = 102;
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

        $list = $this->fetchSensorData($date_from, $date_to, $sensor)->toArray();
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


    public function chartDataView() {
        $sensors = Sensor::select(['measuring_unit', 'value_name'])->get()->unique([ 'value_name']);
        return view('graphs', ['sensors'=>$sensors]);
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
