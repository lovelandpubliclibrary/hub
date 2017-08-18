<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use Session;

class PhotoController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }

    
	public function edit(Photo $photo) {
        // set up breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $photo->incident->id]), 'text' => $photo->incident->title],
            ['link' => route('photo', ['photo' => $photo->id]),
                'text' => 'Photo of ' . ($photo->incident->patron_name ?: 'Unknown Patron')],
        ];

		return view('photos.edit', compact('photo', 'breadcrumbs'));
	}


	public function show(Photo $photo) {
        // set up breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $photo->incident->id]), 'text' => $photo->incident->title],
            ['link' => route('photo', ['photo' => $photo->id]),
                'text' => 'Photo of ' . ($photo->incident->patron_name ?: 'Unknown Patron')],
        ];

		return view('photos.show', compact('photo', 'breadcrumbs'));
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
}
