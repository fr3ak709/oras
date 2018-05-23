<?php

namespace AIVIKS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use AIVIKS\Sensor_data;

class CopyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'copy:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Copys data from devices database, reforms it and adds to the systems database';

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
        //get last data input from systems database
        $date = DB::table('sensor_data')->order_by('date', 'desc')->first()->date;
        //get devices from database        
        $devices = DB::table('devices')->get();
        //get data where date above  the last date and where devices name is from device list  from devices databese 
        $data = DB::table('data')->where('date', '>', $date)->where('devices')->get();
        foreach ($data as $item) {
            if ($devices->contains($item->device)) {

                //choose similar coordinated if there's a match within 0.001 then assign same street
                //else request adress from google as follows
                $lat =  '40.7060006'; 
                $lng = '-74.008801'; 
                $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&key=AIzaSyBxGurAngBBEOYVVW1f--J9KtOlBF-yWtE";
                $response = file_get_contents($url);
                $json = json_decode($response, true);
                $street = $json['results'][0]['address_components'][1]['long_name'];
                $city   = $json['results'][0]['address_components'][4]['long_name'];
                //check if city exists if no create new get id 
                //create new street get its id, assign cities id to it.
            }
        }

        $sensors = DB::table('sensors')->groupBy('name')->get();
        foreach ($data as $item) {
            foreach ($sensors as $sensor) {
                if ($devices->contains($item->device)) {
                    if ( is_set($item->{$sensor->name}) ) { // only the sensors which are placed inside of the system
                        $newItem = new Sensor_data;
                        $newItem->value = $item->{$sensor->name};
                        $newItem->date = $item->created_at;
                        $newItem->streets_id = $item->streets_id;
                        $newItem->save();
                    } 
                }
            }
        }
    }

}
