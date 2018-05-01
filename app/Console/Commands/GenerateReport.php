<?php

namespace AIVIKS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use AIVIKS\Report;
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
        $data = ['string' , 'array', $date];

        $file = PDF::loadView('reports/GeneratedReport', ['data'=>$data]);
        $report = new Report();
        $report->creator_id = 101;
        $report->title = 'generuota ataskaita';
        $report->date = $date;
        $unique_name = $date.'.pdf';
        Storage::disk('public')->put($unique_name, $file->output());
        $report->path = $unique_name;
        $report->save(); 
    }
}
