<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Patron;

class PatronController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }


    public function store(Request $request) {
        // validate the request
    	$rules = [
    		'first_name' => 'string|nullable',
    		'last_name' => 'string|nullable',
    		'description' => 'required',
    		'card_number' => 'length:9|nullable',
    	];

    	$this->validate($request, $rules);

    	$patron = new Patron;
    	$patron->first_name = $request->first_name ?: null;
    	$patron->last_name = $request->last_name ?: null;
    	$patron->description = $request->description;
    	$patron->card_number = $request->card_number ?: null;

    	$patron->save();

    	// set the full name before parsing to JSON
    	$patron->list_name = $patron->get_name('list');
        $patron->full_name = $patron->get_name('full');

		// return a response to the AJAX request
    	return response()->json($patron, 200);
    }

}
