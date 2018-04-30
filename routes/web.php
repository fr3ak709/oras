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
Route::get('reports','ReportController@index')->name('/reports'); 
Route::get('/report/{id}', 'ReportController@download');
Route::get('users', 'SpecialistController@index')->middleware('amAdmin')->name('/users');

Route::get('test', 'ReportController@generate')->name('/test');



Route::get('generate_data','DataController@generate')->name('/generate_data'); 
Route::get('mapData', 'DataController@getMapData');

Route::get('viewReports','ReportController@index')->name('viewReports');
Route::get('viewGraphs', 'DataController@index')->name('viewGraphs');

Route::get('newReport',function () {return view('reports/create');})->name('newReport')->middleware('auth');
Route::get('newUser',function () {return view('specialists/create');})->name('newUser')->middleware('auth');

Route::delete('report/{id}', array('as' => 'report.destroy','uses' => 'ReportController@destroy'))->middleware('auth');
Route::delete('user/{id}', array('as' => 'user.destroy','uses' => 'SpecialistController@destroy'))->middleware('amAdmin');

Route::post('/changePassword','SpecialistController@changePassword')->name('changePassword');
Route::post('/uploadReport', 'ReportController@create');