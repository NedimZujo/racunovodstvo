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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');


Route::get('/home', 'HomeController@index')->name('home');

Route::post('/home', 'HomeController@createAmount');

Route::post('/edit/{id}', 'HomeController@editAmount');

Route::post('update/{id}', 'HomeController@updateAmount');

Route::post('/search', 'HomeController@searchDates');

Route::get('/reports', 'HomeController@reportAmount');

Route::get('/reportdetails/{id}', 'HomeController@reportdetailsAmount');

Route::get('/transfersredstava', 'HomeController@transfersredstava');

Route::get('/banka', 'BankaController@index');

Route::post('/banka', 'BankaController@createAmount');

Route::post('/editbanka/{id}', 'BankaController@editAmount');

Route::post('updatebanka/{id}', 'BankaController@updateAmount');

Route::post('/searchbanka', 'BankaController@searchDates');

Route::get('/bankreports', 'BankaController@reportbankAmount');

Route::get('/bankreportdetails/{id}', 'BankaController@bankreportdetailsAmount');
