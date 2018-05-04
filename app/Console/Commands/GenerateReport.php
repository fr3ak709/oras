<?php

namespace AIVIKS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use AIVIKS\Report;
use AIVIKS\Sensor_data;
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
    {
        $date = date("Y-m-d");
        $data = ['string' , 'array' ];
        $data = Sensor_data::select(
            DB::raw('
                sensors.value_name AS value_name, 
                sensors.value_max AS value_max, 
                sensors.measuring_unit AS measuring_unit,
                AVG(sensor_data.value) AS value,  
                Date(sensor_data.date) AS date ' 
            )
        )
        ->where('value_name', '=', $sensor)
        ->whereBetween('date', [
            date('Y-m-d H:i:s',strtotime('-2 days',strtotime($date ))),  
            $date 
        ])
        ->join('sensors', 'sensors_id', '=', 'sensors.id')
        ->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('h');
        })
        ->orderBy('date')
        ->get();  
        $file = PDF::loadView('reports/GeneratedReport', ['data'=>$data]);
        $report = new Report();
        $report->creator_id = 101;
        $report->title = 'Generuota ataskaita';
        $report->date = $date;
        $unique_name = $date.'.pdf';
        Storage::disk('public')->put($unique_name, $file->output());
        $report->path = $unique_name;
        $report->save();
    }
}
