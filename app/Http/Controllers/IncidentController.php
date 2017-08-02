<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Incident;
use App\Photo;
use Auth;
use Session;

class IncidentController extends Controller
{
    public function index()
    {
        // retrieve all the incidents by date, then time
    	$incidents = Incident::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    	return view('incidents.index', compact('incidents'));
    }


    public function show(Incident $incident)
    {
        // load the comments for the incident
        $comments = $incident->comment;

        // load the photos for the incident
        $photos = $incident->photo;

    	return view('incidents.show', compact('incident', 'comments', 'photos'));
    }


    public function create()
    {
    	return view('incidents.create');
    }


    public function store(Request $request)
    {
        // validate the request input
        $rules = [
            'date' => 'required',
            'title' => 'required',
            'description' => 'required',
            'user' => 'required'
        ];
        $this->validate($request, $rules);

        // store it in a new instance of Incident
        $incident = new Incident;
        $incident->date = $request->date;
        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->user_id = $request->user;
        $incident->patron_name = ($request->patron_name ?: null);
        $incident->card_number = ($request->card_number ?: null);
        $incident->patron_description = ($request->patron_description ?: null);

        // save it to the database, which will give it an id
        if ($incident->save()) {
            // validate and upload the patron photo if necessary
            if ($request->hasFile('patron_photo') && $request->file('patron_photo')->isValid()) {
                // create a unique name for the file
                $filename = uniqid('img_') . '.' . $request->patron_photo->getClientOriginalExtension();
                // move the file to the public/images/patrons/ directory
                if ($request->file('patron_photo')->move(public_path('images/patrons/'), $filename)) {
                    // save the photo id to our instance of incident
                    $incident->photo()->save(Photo::create([
                        'filename' => $filename,
                        'incident_id' => $incident->id]));
                }
            }

            // redirect back the new incident with a success message
            Session::flash('success_message', "Incident Saved - \"$incident->title\"");
            return redirect()->route('incident', ['incident' => $incident->id]);
        }
    }


    public function edit(Incident $incident)
    {
        // make sure the user has permission to edit the incident
        if (Auth::user()->id == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
        {
            // collect the photos associated with this incident
            $photos = $incident->photo;
            return view('incidents.edit', compact('incident', 'photos'));
        }
        else
        {
            // return to the incident with an error message
            $errors = ['Permission Denied' => 'Only ' .
                                              Auth::user()->find($incident->user_id)->name .
                                              ' or a supervisor can modify this incident. You may comment below.'];
            return view('incidents.show', compact('incident', 'errors'));
        }
    }


    public function update(Request $request)
    {
        // validate the form
        $rules = [
            'date' => 'required',
            'title' => 'required',
            'description' => 'required',
            'user' => 'required'
        ];
        $this->validate($request, $rules);

        // retrieve the incident from the database
        $incident = Incident::find($request->incident);

        // retrive the parts of the request we need for the incident
        $updates = $request->only(
            'date',
            'patron_name',
            'card_number',
            'patron_description',
            'title',
            'description'
        );
        
        // set each attribute and save to the database
        foreach ($updates as $key => $value) {
            $incident->$key = $value;
        }
        $incident->save();

        return view('incidents.show', compact('incident'));
    }


    public function search(Request $request)
    {
        // validate the form
        $this->validate($request, ['search' => 'required']);

        // search the database
        $incidents = Incident::where('description',   'LIKE', '%' . $request->search . '%')->
                               orWhere('patron_name', 'LIKE', '%' . $request->search . '%')->
                               orWhere('title',       'LIKE', '%' . $request->search . '%')->
                               orWhereHas('comment', function ($query) use ($request) {
                                   $query->where('comment', 'LIKE', '%' . $request->search . '%');
                               })->get();

        // store the search string to pass back to the view
        $search = $request->search;

        // provide the index view with the search results and search string
        return view('incidents.index', compact('incidents', $request->search));
    }
}
