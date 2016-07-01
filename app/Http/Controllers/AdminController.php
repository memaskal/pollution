<?php

namespace App\Http\Controllers;

use App\Measurements;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function uploadFile(Request $request) {

        $success = false;
        $errors  = Measurements::insertFromFile($request, $success);
        if ($success) {
            return redirect('admin/')
                    ->with(['status' => 'File uploaded successfully!']);
        }
        return redirect('admin/')
                ->withErrors($errors)
                ->withInput();
    }


    public function getIndex()
    {
        return view('admin', ['gmap_key' => \App\Constants::GMAP_KEY,
                            'pol_types' => \App\Constants::POL_TYPES,
                            'stations' => \App\Stations::getStations()]);
    }

}
