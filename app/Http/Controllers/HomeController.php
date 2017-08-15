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
        $unviewed_incidents = Incident::all()->count() - User::find(Auth::id())->incidents->count();
        return view('home', compact('unviewed_incidents'));
    }
}
