<?php

namespace App\Http\Controllers;

use App\Station;
use App\Statistics;
use App\Constants;
use App\Measurement;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * This controller handles all input/output requests
 * in the administrator page
 *
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{

    /**
     * Returns file upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUploadFile() {
        return view('pages.admin.fileUpload', [
            'pol_types' => Constants::POL_TYPES,
            'stations' => Station::getStations()
        ]);
    }

    
    /**
     * Handles file upload request
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postUploadFile(Request $request) {

        $m = new Measurement();
        $m->station_id = $request->input('st_code');
        $m->pollution_type = $request->input('pol_type');
        $inputFile = $request->file('file');

        $success = false;
        $errors = $m->insertFromFile($inputFile, $success);

        if ($success) {
            return redirect('admin/file-upload')
                    ->with(['status' => 'File uploaded successfully!']);
        }
        return redirect('admin/file-upload')
                ->withErrors($errors)
                ->withInput();
    }

    
    /**
     * Returns insert station page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getInsertStation() {
        return view('pages.admin.stationInsert', [
            'gmap_key' => Constants::GMAP_KEY
        ]);
    }

    
    /**
     * Handles insert station request
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postInsertStation(Request $request) {

        $s = new Station();
        $s->id = $request->input('st_code');
        $s->name = $request->input('st_name');
        $s->latitude = $request->input('st_lat');
        $s->longitude = $request->input('st_lng');

        $success = false;
        $errors = $s->insert($success);

        if ($success) {
            return redirect('admin/station-insert')
                ->with(['status' => 'Station inserted successfully!']);
        }
        return redirect('admin/station-insert')
                ->withErrors($errors)
                ->withInput();
    }


    /**
     * Returns delete station page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDeleteStation() {
        return view('pages.admin.stationDelete', [
            'stations' => Station::getStations(5)
        ]);
    }


    /**
     * Handles station delete request
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function delDeleteStation(Request $request) {

        $s = new Station();
        $s->id = $request->input('st_code');

        $success = false;
        $errors = $s->delete($success);

        if ($success) {
            return redirect('admin/station-delete')
                ->with(['status' => 'Station deleted successfully!']);
        }
        return redirect('admin/station-delete')
            ->withErrors($errors)
            ->withInput();
    }


    /**
     * Returns a json formatted response for the auto-refresh
     * statistics page in the admin dashboard
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics() {
        return response()->json([
            'total_req' => Statistics::getRequestsTotal(),
            'total_keys' => Statistics::getAPIKeysTotal(),
            'top_ten'  => Statistics::getTopTenAPIKeys(),
            'status' => 'OK',
        ]);
    }


    /**
     * Returns the index page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex()
    {
        return view('pages.admin.statistics');
    }

}
