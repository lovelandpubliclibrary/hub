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
        return view('reports.index');
    }


    /**
     * Show the Incidents module.
     *
     * @return \Illuminate\Http\Response
     */
    public function incidents() {

        $breadcrumbs = [
            'home',
            'reports',
        ];

        dd($unviewed_by = collect());

        foreach (Incident::all() as $incident) {
            if ($incident->unviewedBy()->isNotEmpty()) {
                dd($incident->unviewedBy());
            } else {
                dd($incident->unviewedBy());
            }
            foreach ($incident->unviewedBy() as $user) {
                foreach ($user->divisions as $division) {
                    if (Auth::user()->divisions->contains($division)) {
                        if (!$unviewed_by->contains($user)) {
                            $unviewed_by->push($user);
                        }
                    }
                }
            }
        }
        
        return view('reports.incidents', compact('unviewed_by', 'breadcrumbs'));
    }
}
