<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Measurement;
use App\Station;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function getUploadFile() {
        return view('pages.admin.fileUpload', [
            'pol_types' => Constants::POL_TYPES,
            'stations' => Station::getStations()
        ]);
    }

    public function postUploadFile(Request $request) {

        $success = false;
        $meas = new Measurement();
        $meas->station_id = $request->input('st_code');
        $meas->pollution_type = $request->input('pol_type');
        $inputFile = $request->file('file');
        $errors = $meas->insertFromFile($inputFile, $success);

        if ($success) {
            return redirect('admin/file-upload')
                    ->with(['status' => 'File uploaded successfully!']);
        }
        return redirect('admin/file-upload')
                ->withErrors($errors)
                ->withInput();
    }


    public function getInsertStation() {
        return view('pages.admin.stationInsert', [
            'gmap_key' => \App\Constants::GMAP_KEY
        ]);
    }
    
    public function postInsertStation(Request $request) {
        
        $success = false;
        $station = new Station();
        $station->id = $request->input('st_code');
        $station->name = $request->input('st_name');
        $station->latitude = $request->input('st_lat');
        $station->longitude = $request->input('st_lng');
        $errors = $station->insert($success);

        if ($success) {
            return redirect('admin/station-insert')
                ->with(['status' => 'Station inserted successfully!']);
        }
        return redirect('admin/station-insert')
                ->withErrors($errors)
                ->withInput();
    }

    public function getDeleteStation() {
        return view('pages.admin.stationDelete', [
            'stations' => Station::getStations(5)
        ]);
    }

    public function postDeleteStation(Request $request) {

        $success = false;
        $station = new Station();
        $station->id = $request->input('st_code');
        $errors = $station->delete($success);

        if ($success) {
            return redirect('admin/station-delete')
                ->with(['status' => 'Station deleted successfully!']);
        }
        return redirect('admin/station-delete')
            ->withErrors($errors)
            ->withInput();
    }


    public function getStatistics() {
        return ['total_req' => \App\Statistics::getRequestsTotal(),
                'total_keys' => \App\Statistics::getAPIKeysTotal(),
                'top_ten'  => \App\Statistics::getTopTenAPIKeys(),
                'status' => 'OK',
        ];
    }

    public function getIndex()
    {
        return view('pages.admin.statistics');
    }

}
