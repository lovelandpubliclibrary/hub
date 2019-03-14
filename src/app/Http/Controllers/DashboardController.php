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
        $supervises_count = $supervises->count() ?: 0;
        $caught_up_count = 0;

        foreach ($supervises as $user) {
            if ($user->unviewedIncidents()->isEmpty()) {
                $caught_up_count++;
            }
        }

        // get an integer percentage to use for progress bar
        $caught_up_ratio = $caught_up_count / $supervises_count;
        $percentage = round($caught_up_ratio * 100);
        $bg_color_calculator = ( floor($caught_up_ratio * 4) / 4 ) * 100;

        $bg_color = 'progress-bar-';
        switch ($bg_color_calculator) {
            case 25:
                $bg_color .= 'warning';
                break;
            case 50:
                $bg_color .= 'info';
                break;
            case 75:
                $bg_color .= 'success';
                break;
            default:
                $bg_color .= 'danger';
                break;
        }

        return view('reports.index', compact('caught_up_count', 'supervises_count', 'percentage', 'bg_color'));
    }
}
