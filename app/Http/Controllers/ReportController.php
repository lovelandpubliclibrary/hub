<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ReportController extends Controller
{
    /**
     * Show the Incidents module.
     *
     * @return \Illuminate\Http\Response
     */
    public function incidents() {

        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('reports'), 'text' => 'Reports'],
        ];

        $direct_reports = Auth::user()->supervises;

        $divisions = Auth::user()->divisions()->wherePivot('supervisor', true)->get();
        $division_users = collect();

        foreach ($divisions as $division) {
            $division_users = $division_users->merge($division->users);
        }
        
        return view('reports.incidents', compact('breadcrumbs', 'direct_reports', 'divisions'));
    }
}
