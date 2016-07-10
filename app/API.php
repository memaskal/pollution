<?php

namespace App;

use Illuminate\Http\Request;
use DB;

/**
 * Model to handle the API's logic
 *
 * Class API
 * @package App
 */
class API
{
    const STATION_REQ    = 1;
    const ABS_VALUE_REQ  = 2;
    const AVG_VALUE_REQ  = 3;

    /**
     * Logs the API call for a the user
     *
     * @param $user_id
     * @param $req_code
     */
    protected static function newRequest($user_id, $req_code) {

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
    public static function exec(Request $request, $req_type, $user) {

        $resp = null;
        switch ($req_type) {
            case API::STATION_REQ:
                $resp = API::getStations($request, $user);
                break;
            case API::ABS_VALUE_REQ:
                $resp = API::getAbsValue($request, $user);
                break;
            case API::AVG_VALUE_REQ:
                $resp = API::getAvgValue($request, $user);
                break;
        }
        // Log request
        if ($resp !== null) {
            API::newRequest($user->id, $req_type);
        }
        return $resp;
    }


    /**
     * Implements the first API call returning all the
     * stations
     *
     * @param Request $request
     * @param $user
     * @return mixed
     */
    protected static function getStations(Request $request, $user) {
        return Station::getStations();
    }


    /**
     * Implements the second API call, returning a single pollution
     * value for a given hour
     *
     * @param Request $request
     * @param $user
     * @return mixed
     */
    protected static function getAbsValue(Request $request, $user) {

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
     * @param $user
     * @return mixed
     */
    protected static function getAvgValue(Request $request, $user) {

        $m = new Measurement();
        $m->pollution_type = $request->pol_type;
        $m->station_id = $request->st_code;
        $sdate = $request->sdate;
        $fdate = $request->fdate;

        return $m->getAvgValue($sdate, $fdate);
    }
}
