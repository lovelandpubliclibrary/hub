<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Incident;

class DashboardController extends Controller
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
    public function index() {
        $unviewed_incidents = Auth::user()->unviewedIncidents()->count();
        return view('home', compact('unviewed_incidents'));
    }


    /**
     * Show the reports dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function reports() {
        $supervises = Auth::user()->supervises;
        $supervises_count = $supervises->count();
        $caught_up_count = 0;

        foreach ($supervises as $user) {
            if ($user->unviewedIncidents()->isEmpty()) {
                $caught_up_count++;
            }
        }

        $percentage = $caught_up_count / $supervises_count;

        return view('reports.index', compact('caught_up_count', 'supervises_count', 'percentage'));
    }
}
