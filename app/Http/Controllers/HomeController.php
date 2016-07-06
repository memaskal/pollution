<?php

namespace App\Http\Controllers;

use App\APIRequest;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * This controller handles the user's homepage
 *
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Use auth middleware so the users
     * are logged in before access.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Return the user's dashboard page
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('pages.home.index', [
            'user'  => $user,
            'stats' => APIRequest::getTotalByType($user->id)
        ]);
    }
}
