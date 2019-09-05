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
   return view('home.index'); 
});

Route::post('/stem','HomeController@stem')->name('stem');

Route::post('/find','HomeController@find')->name('find');



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/coba', 'HomeController@coba')->name('coba');
Route::post('/sendCoba', 'HomeController@send')->name('send');
