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
})->name('schedule');

Route::get('/helpdesk', function() {
	return Redirect::to('http://192.168.1.34/portal');
})->name('helpdesk');

Route::get('/cityemail', function() {
	return Redirect::to('https://fw.ci.loveland.co.us/owa');
})->name('cityemail');


// Incidents
Route::get('/incidents', 'IncidentController@index')->name('incidents');
Route::get('/incidents/create', 'IncidentController@create');
Route::get('/incidents/{incident}', 'IncidentController@show')->name('incident');
Route::post('/incidents/create', 'IncidentController@store');
Route::post('/incidents', 'IncidentController@search');
Route::get('/incidents/edit/{incident}', 'IncidentController@edit');
Route::post('/incidents/edit/{incident]', 'IncidentController@update');

// Comments
Route::post('/comments/create', 'CommentController@store');

// Photos
Route::get('/photos/{photo}', 'PhotoController@show')->name('photo');
Route::get('/photos/edit/{photo}', 'PhotoController@edit')->name('editPhoto');
Route::post('/photos/delete/{photo}', 'PhotoController@delete')->name('deletePhoto');
Route::post('/photos/update/{photo}', 'PhotoController@update')->name('updatePhoto');