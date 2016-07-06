<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

/**
 * Model representing a station instance.
 *
 * Class Station
 * @package App
 */
class Station
{
    public $id;
    public $name;
    public $latitude;
    public $longitude;

    /**
     * Returns all the stations limited to a
     * pagination limit(optional)
     *
     * @param int $pagination
     * @return mixed
     */
    public static function getStations($pagination = 0) {
        if ($pagination <= 0) {
            return DB::table('stations')->get();
        }
        return DB::table('stations')->paginate($pagination);
    }


    /**
     * Validates the insertion of a new station
     * in the database. Returns all the error in
     * the ErrorBag of the Validator object, or else
     * sets the success flag to true.
     *
     * @param $success
     * @return \Illuminate\Validation\Validator
     */
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


    /**
     * Validates the deletion of a station from
     * the database. Returns all the error in
     * the ErrorBag of the Validator object, or else
     * sets the success flag to true.
     *
     * @param $success
     * @return \Illuminate\Validation\Validator
     */
    public function delete( &$success ) {

        $success = false;
        // The validation rules for the station-id
        $validator = Validator::make([
            'station_code' => $this->id,
        ], [
            'station_code' => 'required|max:5',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        try {
            DB::table('stations')
                ->where('id', '=', $this->id)
                ->delete();
            $success = true;
        } catch (QueryException  $e) {
            $validator->errors()->add('Fatal', 'Station code don\'t Exist');
        }
        return $validator;
    }
}
