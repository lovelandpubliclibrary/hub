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

// Default routes
Route::get('/', function () {
    return view('home');
});
Route::get('/home', 'HomeController@index')->name('home');


// Authentication
Auth::routes();


// Navigation routes
Route::get('/schedule', function() {
	return Redirect::to('https://lpl-repository.com/scheduler');
});

Route::get('/helpdesk', function() {
	return Redirect::to('http://192.168.1.34');
});