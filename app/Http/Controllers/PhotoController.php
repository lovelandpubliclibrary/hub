<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;
use Session;

class PhotoController extends Controller
{
	public function edit(Photo $photo) {
		return view('photos.edit', compact('photo'));
	}


	public function show(Photo $photo) {
		return view('photos.show', compact('photo'));
	}


	public function delete(Photo $photo) {
		$incident_id = $photo->incident->id;
		$photo->delete();
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

        Session::flash('success_message', "Caption Saved");
        return redirect()->route('photo', ['photo' => $photo->id]);
    }
}
