<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatron;
use App\Incident;
use App\Patron;
use Illuminate\Http\Request;
use Session;

class PatronController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }


    public function index() {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('patrons'), 'text' => 'Patrons'],
        ];

        $patrons = Patron::all();
        return view('patrons.index', compact('patrons', 'breadcrumbs'));
    }


    public function store(StorePatron $request) {

        // create and save the new patron
        $patron = new Patron;
        $patron->first_name = $request->first_name ?: null;
        $patron->last_name = $request->last_name ?: null;
        $patron->description = $request->description;
        $patron->card_number = $request->card_number ?: null;
        $patron->save();

        // set the full name before returning the object
        $patron->list_name = $patron->get_name('list');
        $patron->full_name = $patron->get_name('full');

        // associate the patron with incident(s) if necessary
        if ($incidents = $request->associatedIncidents) {
            foreach ($incidents as $incident_id) {
                $patron->incident()->attach($incident_id);
            }
        }

        // return a response to the AJAX request
        if ($request->ajax()) {
            return response()->json($patron, 200);
        }

        // redirect to new patron page with a success message
        Session::flash('success_message', "The patron was saved.");
        return redirect()->route('patron', ['patron' => $patron->id]);
    }


    public function create() {
        $incidents = Incident::orderBy('created_at', 'desc')->get();
        return view('patrons.create', compact('incidents'));
    }


    public function search(Request $request) {
        // validate the form
        $this->validate($request, ['search' => 'required']);

        // search the database
        $patrons = Patron::where('description', 'LIKE', '%' . $request->search . '%')->
                               orWhere('first_name', 'LIKE', '%' . $request->search . '%')->
                               orWhere('last_name', 'LIKE', '%' . $request->search . '%')->
                               orWhere('card_number', 'LIKE', '%' . $request->search . '%')->
                               orWhereHas('comments', function ($query) use ($request) {
                                   $query->where('comment', 'LIKE', '%' . $request->search . '%');
                               })->get();

        // store the search string to pass back to the view
        $search = $request->search;

        // provide the index view with the search results and search string
        return view('patrons.index', compact('patrons', 'search'));
    }


    public function show(Patron $patron) {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('patron', ['patron' => $patron->id]), 'text' => $patron->get_name('full')]
        ];

        $comments = $patron->comments;

        // define the source so the view can redirect to the proper location
        // define the source so the view can redirect to the proper location
        $source = [
            'source' => 'patron',
            'id' => $patron->id,
        ];

        return view('patrons.show', compact('breadcrumbs', 'patron', 
                                            'comments', 'source'));
    }

}
