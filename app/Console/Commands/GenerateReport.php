<?php
namespace AIVIKS\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use AIVIKS\Report;
use AIVIKS\Sensor_data;
use AIVIKS\Sensor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Storage;
class GenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:report';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically generates air polution report';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {  //execute from ssh
        $date = date("Y-m-d");
        $sensors = Sensor::select( DB::raw (
            'sensors.value_name AS value_name, 
             sensors.value_max  AS value_max, 
             sensors.measuring_unit AS measuring_unit' ) )
        ->groupBy('value_name')
        ->get();
        
        foreach($sensors as $sensor)
            $sensor->data = Sensor_data::select(
                    DB::raw('
                        AVG(sensor_data.value) AS value,  
                        HOUR(sensor_data.date) AS hour' 
                    )
                )
                ->where('value_name', '=', $sensor->value_name)
                ->whereBetween('date', [
                    date('Y-m-d',strtotime('-1 days',strtotime($date ))),  
                    $date
                ])
                ->join('sensors', 'sensors_id', '=', 'sensors.id')
                ->groupBy(DB::raw('hour'))
                ->orderBy('hour')
                ->get();
        $file = PDF::loadView('reports/GeneratedReport', [
            'sensors'=>$sensors, 
            'date'=>date('Y-m-d',strtotime('-1 days',strtotime($date )))
        ]);
        $report = new Report();
        $report->creator_id = 105;
        $report->title = 'Generuota ataskaita';
        $report->date = $date;
        $unique_name = $date.'.pdf';
        Storage::disk('s3')->put($unique_name, $file->output());
        $report->path = $unique_name;
        $report->save();
    }
}