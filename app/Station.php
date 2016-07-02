<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class Station
{
    public $id;
    public $name;
    public $latitude;
    public $longitude;

    public static function getStations() {
        return DB::table('stations')->get();
    }

    public function insert( &$success ) {

        $success = false;

        // Create the rules for validation
        $validator = Validator::make([
            'station_code' => $this->id,
            'station_name' => $this->name,
            'station_latitude' => $this->latitude,
            'station_longitude' => $this->longitude,
        ], [
            'station_code' => 'required|max:5|unique:stations,id',
            'station_name' => 'required|max:30',
            'station_latitude' => 'required|digits_between:1,18',
            'station_longitude' => 'required|digits_between:1,18',
        ]);

        // validate all inputs
        if ($validator->fails()) {
            return $validator;
        }

        try {
            DB::table('stations')->insert([
                'id' => $this->id,
                'name' => $this->name,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]);
            $success = true;
        } catch (QueryException  $e) {
            $validator->errors()->add('Fatal', 'Database Error');
        }
        return $validator;
    }
}
