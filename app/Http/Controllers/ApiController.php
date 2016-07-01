<?php

namespace App\Http\Controllers;


use App\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

class ApiController extends Controller
{

     public function handle(Request $request, $code) {

        // Check if user has valid token
        $user = Auth::guard('api')->user();

        // Check if user is connected
        if ( !$user ) {
            return ['status' => 'INV_API_KEY'];
        }

        $response = API::exec($request, intval($code), $user);
        if ($response === null) {
            return ['status' => 'INV_REQ_NUM'];
        }
        return ['values' => $response,
                'status' => 'OK'];
    }
}
