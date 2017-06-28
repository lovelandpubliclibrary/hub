<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;

class IncidentController extends Controller
{
    public function index() {
    	$incidents = Incident::all();
    	return view('incidents.index', compact('incidents'));
    }


    public function show(Incident $incident) {
    	return view('incidents.show', compact('incident'));
    }
}
