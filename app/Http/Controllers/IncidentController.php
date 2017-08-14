<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Incident;
use App\Photo;
use App\User;
use Mail;
use App\Mail\IncidentCreated;
use App\Mail\IncidentUpdated;
use Auth;
use Session;

class IncidentController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }

    
    public function index()
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
        ];

        // retrieve the incidents which the user has already viewed
        $user_viewed = Auth::user()->incidents;

        // retrieve all the incidents by date, then time
    	$incidents = Incident::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    	return view('incidents.index', compact('incidents', 'user_viewed', 'breadcrumbs'));
    }


    public function show(Incident $incident)
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $incident->id]), 'text' => $incident->title]
        ];

        // load the comments for the incident
        $comments = $incident->comment;

        // load the photos for the incident
        $photos = $incident->photo;

    	return view('incidents.show', compact('incident', 'comments', 'photos', 'breadcrumbs'));
    }


    public function create()
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('createIncident'), 'text' => 'Report an Incident'],
        ];

    	return view('incidents.create', compact('breadcrumbs'));
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

            // email a notification to all staff
            foreach (User::all() as $user) {
                \Mail::to($user->email)->send(new IncidentCreated($incident));
            }

            // redirect back the new incident with a success message
            Session::flash('success_message', "The incident was saved and an email notification was sent to the library staff.");
            return redirect()->route('incident', ['incident' => $incident->id]);
        }
    }


    public function edit(Incident $incident)
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $incident->id]), 'text' => $incident->title],
        ];

        // make sure the user has permission to edit the incident
        if (Auth::id() == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
        {
            // collect the photos associated with this incident
            $photos = $incident->photo;
            return view('incidents.edit', compact('incident', 'photos', 'breadcrumbs'));
        }
        else
        {
            // return to the incident with an error message
            $errors = ['Permission Denied' => 'Only ' .
                                              Auth::user()->find($incident->user_id)->name .
                                              ' or a supervisor can modify this incident. You may comment below.'];
            return view('incidents.show', compact('incident', 'errors', 'breadcrumbs'));
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

        // record the user who performed the update
        $incident->updated_by = Auth::user()->id;

        // save the updates to the database
        $incident->save();

        // email a notification to the incident creator if someone else modified the incident
        if ($request->user != $incident->user_id) {
            Mail::to($incident->user->email)
                  ->send(new IncidentUpdated($incident));
        }

        Session::flash('success_message', "Incident Updated - \"$incident->title\"");
        return redirect()->route('incident', ['incident' => $incident->id]);
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
        return view('incidents.index', compact('incidents', 'search'));
    }
}
