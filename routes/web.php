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

Route::get('/report/{id}', 'ReportController@download');
Route::get('/device/{id}', 'SensorController@index');

Route::get('test', 'DataController@chartDataView')->name('/test');
Route::get('generate', 'DataController@generate')->name('/generate');

Route::get('generate_data','DataController@generate')->name('/generate_data'); 
Route::get('data', 'DataController@getData');
Route::get('avgdata', 'DataController@getAvgData');
Route::get('dataDownload', 'DataController@dataDownloadView')->name('/dataDownload');

Route::get('viewReports', 'ReportController@index')->name('viewReports');
Route::get('viewGraphs', 'DataController@index')->name('viewGraphs');

Route::get('newReport',function () {return view('reports/create');})->name('newReport');
Route::get('newDevice',function () {return view('devices/create');})->name('newDevice');
Route::get('newUser',  function () {return view('specialists/create');})->name('newUser');
Route::get('newSensor/{id}', 'SensorController@createView')->name('newSensor');

Route::delete('report/{id}', array('as' => 'report.destroy','uses' => 'ReportController@destroy'));
Route::delete('user/{id}', array('as' => 'user.destroy','uses' => 'SpecialistController@destroy'));
Route::delete('device/{id}', array('as' => 'device.destroy','uses' => 'DeviceController@destroy'));
Route::delete('devices_sensor/{id}', array('as' => 'sensor.destroy','uses' => 'SensorController@destroy'));

Route::post('/changePassword','SpecialistController@changePassword')->name('changePassword');
Route::post('/uploadReport', 'ReportController@create');
Route::post('/addDevice', 'DeviceController@create');
Route::post('/addSensor/{id}', 'SensorController@create');

Route::post('/downloadCSV', 'DataController@downloadCSV');



Route::get('createAdmin', 'Auth\ResetPasswordController@createAdmin')->name('/createAdmin');