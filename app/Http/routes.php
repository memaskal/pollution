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
 * Route for all the API requests. Uses the API middleware
 * to throttle connections to 60/s
 */
Route::group(['prefix' => 'api', 'middleware' => 'api'], function () {
    Route::get('/{code}', 'ApiController@handle')->where('code', '[0-9]+');
});


/**
 * Routes for the administrator directory. Using auth and the user-defined
 * "admin" middleware to restrict access.
 */
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
    Route::delete('/station-delete', 'AdminController@delDeleteStation');
});


/**
 * Routes for the demo page
 */
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


/**
 * Routes for login/register/password-reset pages and user's homepage
 */
Route::auth();
Route::get('/', 'HomeController@index');
