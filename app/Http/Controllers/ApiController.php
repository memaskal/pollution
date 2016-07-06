<?php

namespace App\Http\Controllers;


use App\API;
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
    /**
     * Checks if the API-key is valid as well as the
     * requested API call.
     *
     * @param Request $request
     * @param $code
     * @return array
     */
     public function handle(Request $request, $code) {

        // Get user by its API-key
        $user = Auth::guard('api')->user();

        // Check if we have a valid user
        if ( !$user ) {
            return ['status' => 'INV_API_KEY'];
        }

        // Request the data
        $response = API::exec($request, intval($code), $user);
        if ($response === null) {
            // Invalid API call
            return ['status' => 'INV_REQ_NUM'];
        }
        return ['values' => $response,
                'status' => 'OK'];
    }
}
