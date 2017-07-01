<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Incident;

class IncidentController extends Controller
{
    public function index() {
    	$incidents = Incident::orderBy('date', 'desc')->get();
    	return view('incidents.index', compact('incidents'));
    }


    public function show(Incident $incident) {
    	return view('incidents.show', compact('incident'));
    }


    public function create() {
    	return view('incidents.create');
    }


    public function store(Request $request) {
        $rules = [
            'date' => 'required',
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required'
        ];

        $this->validate($request, $rules);
    }
}
