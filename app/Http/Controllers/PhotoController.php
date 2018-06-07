<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Photo;
use App\Patron;
use Session;

class PhotoController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }


        public function store(Request $request) {
            $errors = [];       // placeholder for errors
            // return response()->json($request);

            // validate the request
            $rules = [
                'photo' => 'file|required',
                'caption' => 'string|nullable',
                'associatingPatrons' => 'boolean|nullable',
                'associatedPatrons' =>'string|nullable',
            ];

            $this->validate($request, $rules);

            // validate the uploaded photo
            $file = $request->file('photo');
            $accepted_image_formats = [     // https://laravel.com/docs/5.4/requests#retrieving-input
                'jpeg',
                'png',
                'gif',
            ];

            // ensure the file is valid
            if ($file->isValid() && in_array(strtolower($file->extension()), $accepted_image_formats)) {
                // collect or determine values we need to save the photo
                $timestamp = time();
                $file_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) .
                                "_{$timestamp}.{$file->extension()}";

                // save to the filesystem and set visibility
                $stored_path = $file->storeAs('photos', $file_name, 'public');

                if ($stored_path) {
                    // save to the database and confirm
                    $photo = new Photo;
                    $photo->filename = $file_name;
                    $photo->caption = $request->caption ?: null;
                    $photo->save();
                    $photo->url = asset("/storage/photos/{$file_name}");

                    if (!$photo->id) {  // will have an ID if saved successfully
                        $errors[] = 'There was a problem saving to the database.';
                    }
                } else {
                    $errors[] = 'There was a problem saving the file to the filesystem.';
                }

                // associate patrons
                $associated_patrons = $request->associatedPatrons;
                $patron_ids = $associated_patrons ? preg_split('/,/', $associated_patrons) : false;
                if ($patron_ids) {
                    foreach ($patron_ids as $patron_id) {
                        $photo->patron()->save(Patron::find($patron_id));
                    }

                    if (!count($photo->patron)) {
                        $errors[] = 'There was a problem associating patrons to the photo.';
                    }
                }
                
            } else {
                $errors[] = 'The selected photo is not valid.';
            }

            if (count($errors) === 0) {     // no errors
                // return a response to the AJAX request
                return response()->json($photo, 200);
            }

            // something went wrong, return the errors
            return response()->json($errors, 200);
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
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $photo->incident_id]), 'text' => $photo->incident->title],
            ['link' => route('photo', ['photo' => $photo->id]),
                'text' => 'Photo of ' . $patron->get_full_name()],
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
}
