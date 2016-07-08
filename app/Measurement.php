<?php

namespace App;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Validator;

/**
 * Model representing a measurement instance.
 *
 * Class Measurement
 * @package App
 */
class Measurement
{
    public $station_id;
    public $pollution_type;
    public $date;
    public $hour;

    const INVALID_VALUE  = -9999;

    /**
     * Returns the measurement value for a specific time in a date
     * for a given pollution type taken at a single station or all
     * stations
     *
     * @return mixed
     */
    public function getAbsValue() {

        $query = DB::table('measurements')
            ->join('measurement_values', 'measurement_id', '=', 'measurements.id')
            ->join('stations', 'station_id', '=', 'stations.id')
            ->select('latitude', 'longitude', 'value as abs')
            ->where('pollution_type', '=', $this->pollution_type)
            ->where('date', '=', $this->date)
            ->where('hour', '=', $this->hour);

        if ($this->station_id != '') {
            $query = $query->where('stations.id', '=', $this->station_id);
        }
        return $query->get();
    }


    /**
     * Returns the average value of a pollution type for a given
     * time period in a single or more stations
     *
     * @param $sdate
     * @param $fdate
     * @return mixed
     */
    public function getAvgValue($sdate, $fdate) {

        $query = DB::table('measurements')
            ->select('latitude', 'longitude', DB::raw('avg(value) as avg, stddev(value) as s'))
            ->join('measurement_values', 'measurement_id', '=', 'measurements.id')
            ->join('stations', 'station_id', '=', 'stations.id')
            ->groupBy('station_id')
            ->where('pollution_type', '=', $this->pollution_type)
            ->whereBetween('date', array($sdate, $fdate));

        if ($this->station_id != '') {
            $query = $query->where('stations.id', '=', $this->station_id);
        }
        return $query->get();
    }


    /**
     * Parses the uploaded file and inserts the
     * measurements to the database
     * 
     * @param $success
     * @param UploadedFile $file
     * @return \Illuminate\Validation\Validator
     */
    public function insertFromFile(UploadedFile $file, &$success) {

        $success = false;

        // Pollution types to string
        $pol_type_arr = implode(",", Constants::POL_TYPES);

        // Create the rules for validation
        $validator = Validator::make([
            'pollution_type' => $this->pollution_type,
            'station_code' => $this->station_id,
            'file' => $file
        ], [
            'pollution_type' => 'required|max:5|in:'.$pol_type_arr,
            'station_code' => 'required|max:5|exists:stations,id',
            'file' => 'required|file|mimes:csv,txt'
        ]);

        // validate all inputs
        if ($validator->fails()) {
            return $validator;
        }

        function validateDate( $date ) {
           if (($timestamp = strtotime($date)) === false) {
               return 'invalid';
           }
           return date('Y-m-d', $timestamp);
        }
        $values = [];

        // Read file as much as possible
        $input = fopen($file->getRealPath(), "r");
        while(!feof($input)) {

            $format_error = false;
            // Split every line into columns separated by commas
            $fields = explode(',', fgets($input));
            // For extra new lines or text lines at beginning
            if (count($fields) != 25) continue;

            // Validate date format
            if (($date = validateDate(str_replace('"', '', $fields[0]))) === 'invalid') {
                $format_error = true;
            }
            else {
                $measurements = array(24);
                for ($i = 1; $i < 25; ++$i) {
                    if (!is_numeric(trim($fields[$i]))) {
                        $format_error = true;
                        break;
                    }
                    $measurements[$i - 1] = floatval($fields[$i]);
                }
                $values[$date] = $measurements;
            }

            // Check for format error and return
            if ($format_error) {
                break;
            }
        }
        fclose($input);

       if ($format_error || count($values) == 0) {
           $validator->errors()->add('file', 'Bad file format detected');
           return $validator;
       }

        // Everything is right so we insert all the values
        // with a transaction to the database
       try {
           // Begin a transaction
           DB::beginTransaction();

           // A set of queries; if one fails, an exception should be thrown
           foreach ($values as $date => $measurements) {
               $id = DB::table('measurements')->insertGetId(
                   ['station_id' => $this->station_id, 'pollution_type' => $this->pollution_type, 'date' => $date]
               );
               $insert_values = [];
               foreach ($measurements as $indx => $value) {
                   $hour = $indx + 1;
                   // Don't insert hours with invalid times
                   if ( $value != Measurement::INVALID_VALUE ) {
                       array_push($insert_values, [
                           'measurement_id' => $id, 'hour' => $hour, 'value' => $value
                       ]);
                   }
               }
               DB::table('measurement_values')->insert($insert_values);
           }

           // If we arrive here, it means that no exception was thrown
           // i.e. no query has failed, and we can commit the transaction
           DB::commit();
           $success = true;
       } catch (QueryException  $e) {
           // An exception has been thrown
           // We must rollback the transaction
           DB::rollBack();
           $validator->errors()->add('file', 'Possible duplicate entries in file');
       }
       return $validator;
    }
}
