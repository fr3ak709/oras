<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get ('/home', 'HomeController@index')->name('/home');\
Auth::routes();
Route::get('/changePassword','SpecialistController@showChangePasswordForm');

Route::get ('/', function() {
    return view('/welcome'); })->name('/');
    
Route::get('reports','ReportController@editReports')->name('/reports')->middleware('auth'); 
Route::get('users', 'SpecialistController@index')->middleware('amAdmin')->name('/users');
Route::get('devices','DeviceController@index')->name('/devices'); 
Route::get('sensors', 'SensorController@sensorsView')->name('/sensors');

Route::get('/report/{id}', 'ReportController@download');
Route::get('/device/{id}', 'SensorController@devicesSensorsView');

//debuging actions
Route::get('test', 'SensorController@sensorsView')->name('/test');
Route::get('generate', 'DataController@generate')->name('/generate');

// actions to reqcieve data
Route::get('generate_data','DataController@generate')->name('/generate_data'); 
Route::get('data', 'DataController@getData');
Route::get('avgdata', 'DataController@getAvgData');
Route::get('dataDownload', 'DataController@dataDownloadView')->middleware('auth')->name('/dataDownload');

// Guest navigation options 
Route::get('viewReports', 'ReportController@index')->name('viewReports');
Route::get('map', 'DataController@index')->name('map');
Route::get('graph', 'DataController@chartDataView')->name('graph');

Route::delete('report/{id}', array('as' => 'report.destroy','uses' => 'ReportController@destroy'));
Route::delete('user/{id}', array('as' => 'user.destroy','uses' => 'SpecialistController@destroy'));
Route::delete('device/{id}', array('as' => 'device.destroy','uses' => 'DeviceController@destroy'));
Route::delete('devices_sensor/{id}', array('as' => 'sensor.removeFromDevice','uses' => 'SensorController@removeFromDevice'));
Route::delete('sensor/{id}', array('as' => 'sensor.destroy','uses' => 'SensorController@destroy'));

//create new stuff 
Route::post('/changePassword','SpecialistController@changePassword')->name('changePassword');
Route::post('/uploadReport', 'ReportController@create');
Route::post('/addDevice', 'DeviceController@create');
Route::post('/addSensor/{id}', 'SensorController@addSensorToDevice');
Route::post('/createSensor', 'SensorController@create');
Route::post('/updateSensor', 'SensorController@update');
Route::post('/downloadCSV', 'DataController@downloadCSV');



Route::get('createAdmin', 'Auth\ResetPasswordController@createAdmin')->name('/createAdmin');