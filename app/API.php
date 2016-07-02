<?php

namespace App;

use Illuminate\Http\Request;
use DB;

abstract class API
{

    const STATION_REQ    = 1;
    const ABS_VALUE_REQ  = 2;
    const AVG_VALUE_REQ  = 3;
    
    const INVALID_MEASUREMENT_VALUE  = -9999;

    protected static function newRequest($user_id, $req_code) {
        // log the request to database
        DB::table('requests')->insert(
            ['user_id' => $user_id, 'request_type' => $req_code]
        );
    }

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
    
    protected static function getStations(Request $request, $user) {
        // Log request
        API::newRequest($user->id, API::STATION_REQ);
        return Station::getStations();
    }

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
            ->select('latitude', 'longitude', DB::raw('value as abs'))
            ->where('pollution_type', '=', $pol_type)
            ->where('date', '=', $date)
            ->where('hour', '=', $hour);

        if ($st_code != 0) {
            $query = $query->where('stations.id', '=', $st_code);
        }
        return $query->get();
    }

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
