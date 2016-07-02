<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/**
 * Enables api middleware for throttle in 60 connections/s
 */
Route::group(['middleware' => 'api'], function () {
    Route::get('api/{code}', 'ApiController@handle')->where('code', '[0-9]+');
});


// for admin
Route::group(['prefix' => 'admin'], function () {
    
    Route::get('/', 'AdminController@getIndex');
    Route::post('/upload', 'AdminController@uploadFile');
    Route::post('/station', 'AdminController@insertStation');
});


Route::group(['prefix' => 'demo'], function () {

    Route::get('/', 'DemoController@getIndex');

    // Handles requests to the api
    Route::get('/reqStations', 'DemoRequestsController@getStations');
    Route::get('/reqAbsValue', 'DemoRequestsController@getAbsValue');
    Route::get('/reqAvgValue', 'DemoRequestsController@getAvgValue');
});
