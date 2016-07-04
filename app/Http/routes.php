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
Route::group(['prefix' => 'api'], function () {
    Route::get('/{code}',  [
            'middleware' => 'api',
            'uses' => 'ApiController@handle'
    ])->where('code', '[0-9]+');
});


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    // Index page with statistics
    Route::get('/', 'AdminController@getIndex');
    Route::get('/stats', 'AdminController@getStatistics');
    
    // File upload
    Route::get('/file-upload', 'AdminController@getUploadFile');
    Route::post('/file-upload', 'AdminController@postUploadFile');

    // Station insert
    Route::get('/station-insert', 'AdminController@getInsertStation');
    Route::post('/station-insert', 'AdminController@postInsertStation');

    // Station delete
    Route::get('/station-delete', 'AdminController@getDeleteStation');
    Route::delete('/station-delete', 'AdminController@postDeleteStation');
});


Route::group(['prefix' => 'demo'], function () {

    // Returns the demo index page
    Route::get('/', function () {
        return view('pages.demo.index', ['gmap_key' => \App\Constants::GMAP_KEY,
                                         'pol_types' => \App\Constants::POL_TYPES]);
    });

    // Handles requests to the api
    Route::get('/reqStations', 'DemoRequestsController@getStations');
    Route::get('/reqAbsValue', 'DemoRequestsController@getAbsValue');
    Route::get('/reqAvgValue', 'DemoRequestsController@getAvgValue');
});


Route::auth();
Route::get('/', 'HomeController@index');
