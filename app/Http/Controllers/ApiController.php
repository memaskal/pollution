<?php

namespace App\Http\Controllers;


use App\Station;
use App\Measurement;
use App\APIRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

/**
 * This controller handles all the requests to the API.
 *
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    const STATION_REQ    = 1;
    const ABS_VALUE_REQ  = 2;
    const AVG_VALUE_REQ  = 3;

    /**
     * Checks if the API-key is valid as well as the
     * requested API call.
     *
     * @param Request $request
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
     public function handle(Request $request, $code) {

        // Get user by its API-key
        $user = Auth::guard('api')->user();

         // Check if we have a valid user
         if ( !$user ) {
             return self::error('INV_API_KEY');
         }

        // Request the data
        $response = $this->exec($request, intval($code), $user);
        if ($response === null) {
            // Invalid API call
            return self::error('INV_REQ_NUM');
        }
        return self::success($response);
    }

    /**
     * Returns an error status message in json
     *
     * @param $err_code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($err_code) {
        return response()->json(['status' => $err_code]);
    }

    /**
     * Returns a json response upon success
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data) {
        return response()->json(['values' => $data, 'status' => 'OK']);
    }


    /**
     * Logs the API call for a the user
     *
     * @param $user_id
     * @param $req_code
     */
    protected function newRequest($user_id, $req_code) {

        $request = new APIRequest();
        $request->user_id = $user_id;
        $request->request_type = $req_code;
        $request->save();
    }


    /**
     * Finds the appropriate API call
     *
     * @param Request $request
     * @param $req_type
     * @param $user
     * @return mixed|null
     */
    protected function exec(Request $request, $req_type, $user) {

        $resp = null;
        switch ($req_type) {
            case self::STATION_REQ:
                $resp = $this->getStations();
                break;
            case self::ABS_VALUE_REQ:
                $resp = $this->getAbsValue($request);
                break;
            case self::AVG_VALUE_REQ:
                $resp = $this->getAvgValue($request);
                break;
        }
        // Log request
        if ($resp !== null) {
            $this->newRequest($user->id, $req_type);
        }
        return $resp;
    }


    /**
     * Implements the first API call returning all the
     * stations
     *
     * @return mixed
     */
    protected function getStations() {
        return Station::getStations();
    }


    /**
     * Implements the second API call, returning a single pollution
     * value for a given hour
     *
     * @param Request $request
     * @return mixed
     */
    protected function getAbsValue(Request $request) {

        $m = new Measurement();
        $m->pollution_type = $request->pol_type;
        $m->station_id = $request->st_code;
        $m->date = $request->date;
        $m->hour = $request->hour;

        return $m->getAbsValue();
    }


    /**
     * Implements the third API call, returning the average
     * and standard deviation for given pollution type
     * in a time period.
     *
     * @param Request $request
     * @return mixed
     */
    protected function getAvgValue(Request $request) {

        $m = new Measurement();
        $m->pollution_type = $request->pol_type;
        $m->station_id = $request->st_code;
        $sdate = $request->sdate;
        $fdate = $request->fdate;

        return $m->getAvgValue($sdate, $fdate);
    }
}
