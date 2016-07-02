<?php

namespace App\Http\Controllers;

use App\Measurement;
use App\Station;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function uploadFile(Request $request) {

        $success = false;

        $meas = new Measurement();
        $meas->station_id = $request->input('st_code');
        $meas->pollution_type = $request->input('pol_type');
        $inputFile = $request->file('file');

        $errors = $meas->insertFromFile($inputFile, $success);
        if ($success) {
            return redirect('admin/')
                    ->with(['status' => 'File uploaded successfully!']);
        }
        return redirect('admin/')
                ->withErrors($errors)
                ->withInput();
    }


    public function insertStation(Request $request) {
        
        $success = false;

        $station = new Station();
        $station->id = $request->input('st_code');
        $station->name = $request->input('st_name');
        $station->latitude = $request->input('st_lat');
        $station->longitude = $request->input('st_lng');
        $errors = $station->insert($success);

        if ($success) {
            return redirect('admin/')
                ->with(['status' => 'Station inserted successfully!']);
        }
        return redirect('admin/')
                ->withErrors($errors)
                ->withInput();
    }


    public function getIndex()
    {
        return view('admin', ['gmap_key' => \App\Constants::GMAP_KEY,
                            'pol_types' => \App\Constants::POL_TYPES,
                            'stations' => \App\Station::getStations()]);
    }

}
