<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
    	return view('reports.index');
    }
}
