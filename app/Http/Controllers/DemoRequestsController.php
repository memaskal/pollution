<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

/**
 * The purpose of this class is to make "realistic" HTTP/GET calls
 * to the api as it existed in a different server. Plus it
 * hides the developer's API key.
 *
 * Class DemoRequestsController
 * @package App\Http\Controllers
 */
class DemoRequestsController extends Controller
{
    protected $STATION_REQ    = 1;
    protected $ABS_VALUE_REQ  = 2;
    protected $AVG_VALUE_REQ  = 3;

    // Demo site developer API key
    protected $API_KEY = "12345";

    /**
     * Function makeRequest is called provided a parameter
     * url to be sent to the api and the type of request.
     * It handles possible network errors and returns a
     * json formatted response.
     *
     * @param $type
     * @param $params
     * @return array|mixed
     */
    protected function makeRequest( $type, $params ) {

        $ch = curl_init(url('api')."/$type?$params&api_token=$this->API_KEY");
        // return response as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // wait max 5 seconds for response
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);

        if(($response = curl_exec($ch)) === false) {
            $response = ['status' => 'SERVER_ERROR'];
        }
        curl_close($ch);
        return $response;
    }

    /**
     * Sets the parameters for the first API call
     *
     * @param Request $request
     * @return array|mixed
     */
    public function getStations(Request $request) {
        return $this->makeRequest($this->STATION_REQ, '');
    }


    /**
     * Sets the parameters for the second API call
     *
     * @param Request $request
     * @return array|mixed
     */
    public function getAbsValue(Request $request) {
        $params = '';
        $fields = ['pol_type', 'st_code', 'date', 'hour'];
        foreach ($fields as $field) {
            $value = $request->input($field, '');
            $params.="&$field=$value";
        }
        return $this->makeRequest($this->ABS_VALUE_REQ, $params);
    }


    /**
     * Sets the parameters for the third API call
     *
     * @param Request $request
     * @return array|mixed
     */
    public function getAvgValue(Request $request) {
        $params = '';
        $fields = ['pol_type', 'st_code', 'sdate', 'fdate'];
        foreach ($fields as $field) {
            $value = $request->input($field,'');
            $params.="&$field=$value";
        }
        return $this->makeRequest($this->AVG_VALUE_REQ, $params);
    }
}
