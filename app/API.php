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
    
    const INVALID_MEASUREMENT_VALUE  = -9999;

    /**
     * Logs the API call for a the user
     *
     * @param $user_id
     * @param $req_code
     */
    protected static function newRequest($user_id, $req_code) {
        // log the request to database
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
     * @return array|mixed|null|static[]
     */
    public static function exec(Request $request, $req_type, $user) {
        switch ($req_type) {
            case API::STATION_REQ:
                return API::getStations($request, $user);
            case API::ABS_VALUE_REQ:
                return API::getAbsValue($request, $user);
            case API::AVG_VALUE_REQ:
                return API::getAvgValue($request, $user);
        }
        // Invalid request type
        return null;
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
        // Log request
        API::newRequest($user->id, API::STATION_REQ);
        return Station::getStations();
    }


    /**
     * Implements the second API call, returning a single pollution
     * value for a given hour
     *
     * @param Request $request
     * @param $user
     * @return array|static[]
     */
    protected static function getAbsValue(Request $request, $user) {

        // Log request
        API::newRequest($user->id, API::ABS_VALUE_REQ);

        $pol_type = $request->pol_type;
        $st_code  = $request->st_code;
        $date     = $request->date;
        $hour     = (int)$request->hour;

        $query = DB::table('measurements')
            ->join('measurement_values', 'measurement_id', '=', 'measurements.id')
            ->join('stations', 'station_id', '=', 'stations.id')
            ->select('latitude', 'longitude', 'value as abs')
            ->where('pollution_type', '=', $pol_type)
            ->where('date', '=', $date)
            ->where('hour', '=', $hour);

        if ($st_code != '') {
            $query = $query->where('stations.id', '=', $st_code);
        }
        return $query->get();
    }


    /**
     * Implements the third API call, returning the average
     * and standard deviation for given pollution type
     * in a time period.
     *
     * @param Request $request
     * @param $user
     * @return array|static[]
     */
    protected static function getAvgValue(Request $request, $user) {

        // Log request
        API::newRequest($user->id, API::AVG_VALUE_REQ);

        $pol_type = $request->pol_type;
        $st_code  = $request->st_code;
        $sdate    = $request->sdate;
        $fdate    = $request->fdate;

        $query = DB::table('measurements')
            ->select('latitude', 'longitude', DB::raw('avg(value) as avg, stddev(value) as s'))
            ->join('measurement_values', 'measurement_id', '=', 'measurements.id')
            ->join('stations', 'station_id', '=', 'stations.id')
            ->groupBy('station_id')
            ->where('pollution_type', '=', $pol_type)
            ->where('value', '<>', API::INVALID_MEASUREMENT_VALUE)
            ->whereBetween('date', array($sdate, $fdate));

        if ($st_code != '') {
            $query = $query->where('stations.id', '=', $st_code);
        }
        return $query->get();
    }
}
