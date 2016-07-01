<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class DemoController extends Controller
{
    public function getIndex() {
        return view('welcome', ['gmap_key' => \App\Constants::GMAP_KEY,
                                'pol_types' => \App\Constants::POL_TYPES]);
    }
}
