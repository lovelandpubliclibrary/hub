<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Incident;
use App\User;

class HomeController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $incident_count = Incident::all()->count();
        $user_count = User::find(Auth::id())->incidents->count();
        return view('home', compact('incident_count', 'user_count'));
    }
}
