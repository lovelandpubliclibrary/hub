<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Photo;
use App\Patron;
use App\Incident;
use Session;
use App\Http\Requests\StorePhoto;

class PhotoController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }


    public function index()
    {
        // setup breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('photos'), 'text' => 'Photos'],
        ];

        $photos = Photo::orderBy('created_at', 'desc')->get();

        return view('photos.index', compact('breadcrumbs', 'photos'));
    }


    public function store(StorePhoto $request) {
        // collect resources
        $file = $request->file('photo');
        $photo = new Photo;
        $errors = [];

        // collect and determine the values we need to save the photo
        $timestamp = time();
        $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                        "_{$timestamp}.{$file->extension()}";

        // save to the filesystem and set visibility
        $stored_path = $file->storeAs('photos', $file_name, 'public');

        // save to the database and confirm
        $photo->filename = $file_name;
        $photo->caption = $request->caption ?: null;
        $photo->save();     // required in order to save relationships
        $photo->url = asset("/storage/photos/{$file_name}");

        // associate patrons and confirm
        if (isset($request->associatedPatrons)) {
            foreach ($request->associatedPatrons as $patron_id) {
                $photo->patron()->attach(Patron::find($patron_id));
            }            
            
            if (!count($photo->patron)) {
                $errors[] = 'There was a problem associating patron(s) with this photo.';
            }
        }

        // associate incident and confirm
        if (isset($request->associatedIncident)) {
            $photo->incident()->attach(Incident::find($request->associatedIncident));

            if (!count($photo->incident)) {
                $errors[] = 'There was a problem associating the incident with this photo.';
            } 
        }
        
        // something went wrong, return the errors
        if (count($errors)) {
            if ($request->ajax()) {
                return response()->json($errors, 500);
            }

            // redirect browser back to form with error messages
            return redirect()->back()->withErrors($errors);
        }

        // return response to ajax request
        if ($request->ajax()) {
            return response()->json($photo, 200);
        }

        // redirect browser to new photo
        Session::flash('success_message', "The photo was saved.");
        return redirect()->route('photo', ['photo' => $photo->id]);
    }
    

	public function edit(Photo $photo) {
        // set up breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $photo->incident->id]), 'text' => $photo->incident->title],
            ['link' => route('photo', ['photo' => $photo->id]),
                'text' => 'Photo of ' . ($photo->patron ?: 'Unknown Patron')],
        ];

		return view('photos.edit', compact('photo', 'breadcrumbs'));
	}


	public function show(Photo $photo) {
        $patron = $photo->patron->first() ?: new Patron;

        // set up breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('photos'), 'text' => 'Photos'],
            ['link' => route('photo', ['photo' => $photo->id]),
                'text' => 'Photo of ' . $patron->get_name('full')],
        ];

		return view('photos.show', compact('photo', 'patron', 'breadcrumbs'));
	}


	public function delete(Photo $photo) {
		$incident_id = $photo->incident->id;
		$photo->delete();
        Session::flash('success_message', 'Photo deleted.');
		return redirect()->route('incident', ['incident' => $incident_id]);
	}


	public function update(Request $request) {
        // validate the form
        $rules = [
            'caption' => 'required'
        ];
        $this->validate($request, $rules);

        // retrieve the incident from the database
        $photo = Photo::find($request->photo);

        // retrive the parts of the request we need for the incident
        $updates = $request->only(
            'caption'
        );
        
        // set each attribute and save to the database
        foreach ($updates as $key => $value) {
            $photo->$key = $value;
        }
        $photo->save();

        Session::flash('success_message', "Caption Updated.");
        return redirect()->route('photo', ['photo' => $photo->id]);
    }


    public function create() {
        // create breadcrumbs
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('patrons'), 'text' => 'Patrons'],
        ];

        $patrons = Patron::all()->sortByDesc('created_at');     // most recent first
        $incidents = Incident::all()->sortByDesc('created_at');

        return view('photos.create', compact('breadcrumbs', 'patrons', 'incidents'));
    }
}
