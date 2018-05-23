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

    public function testing() {
        $date = date("Y-m-d");
     /*    $sensors = Sensor::select( DB::raw (
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
            );   */
        //echo $data[0][1]->value;
        $data = $this->fetchAvgSensorData( 
            date('Y-m-d',strtotime('-7 days',strtotime($date ))), 
            $date, 
            'co');
         return view('graph', ['data'=>$data]);
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
        foreach ($data as $item) {
            $item->value = number_format($item->value, 2, '.', '');

        }
        return $data;
    }
    public function fetchAvgSensorData ($date_from, $date_to, $sensor) {
        
        $date = date('Y-m-d H:i:s');
        $data = Sensor_data::select(
            DB::raw('
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
        $startdate = date('Y-m-d H:i:s',strtotime('-1 days',strtotime($startdate)));
       // $startdate = date('Y-m-d H:i:s',strtotime('+2 days',strtotime($startdate)));
        $minLat  = 54.82; $maxLat  = 54.96;
        $minLong = 23.76; $maxLong = 24.10;
        $mil = 1000000;

        $date = $startdate;
        $sensors = Sensor::all();
        foreach($sensors as $sensor) {
            echo $sensor->value_name;
            echo $startdate;
            $date  = $startdate;

            for ($i = 0; $i < 100; $i++) {
                $data = new Sensor_data;
                $date = date('Y-m-d H:i:s',strtotime('+1 minutes',strtotime($date)));
                $data->date = $date;
                $data->lat =  rand((int)($minLat *$mil), (int)($maxLat *$mil))/$mil;
                $data->long = rand((int)($minLong*$mil), (int)($maxLong*$mil))/$mil;
                $data->value =  rand((int)($sensor->value_max/2 *$mil), (int)(($sensor->value_max+50) *$mil))/$mil;
                $data->sensors_id = $sensor->id;
                $data->save();
            }
        }

        $date = $startdate;

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
        return view('graph', ['sensors'=>$sensors]);
    }

    public function dataDownloadView()
    {        
        $sensors = Sensor::select(['measuring_unit', 'value_name', 'value_max'])->get()->unique([ 'value_name']);
        return view('GetDataView', ['sensors'=>$sensors]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $sensors = Sensor::select(['measuring_unit', 'value_name', 'value_max'])->get()->unique([ 'value_name']);
        return view('map', ['sensors'=>$sensors]);
    }



    public function test(){
        $coordinateslat =  '40.7060006';
        $coordinateslng = '-74.008801';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$coordinateslat.",".$coordinateslng."&key=AIzaSyBxGurAngBBEOYVVW1f--J9KtOlBF-yWtE";
        $address = urlencode("Wall Street, New York");
       // $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
        $response = file_get_contents($url);
        $json = json_decode($response, true);
     
       // $lat = $json['results'][0]['geometry']['location']['lat'];
       // $lng = $json['results'][0]['geometry']['location']['lng'];

/*         
        foreach ($json['results'][0]['address_components'][1] as $result) {
            echo $result;
        } 
        https://developers.google.com/maps/documentation/geocoding/intro#ReverseGeocoding
 */        
        echo 'test strat-';
        echo $json['results'][0]['address_components'][1]['long_name'];//[1]['long_name'] ;
        echo $json['results'][0]['address_components'][4]['long_name'];//[1]['long_name'] ;
        echo '-test end';
    }
}
