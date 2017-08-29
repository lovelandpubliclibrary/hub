<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Incident;
use App\Photo;
use App\User;
use App\Location;
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

    
    public function index() {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
        ];

        // retrieve the incidents which the user has already viewed
        $user_viewed = Auth::user()->incidentsViewed;

        // retrieve all the incidents by date, then time
    	$incidents = Incident::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    	return view('incidents.index', compact('incidents', 'user_viewed', 'breadcrumbs'));
    }


    public function show(Incident $incident) {
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

        // record that the user viewed this incident
        $user = Auth::user();
        if (!$user->incidentsViewed->contains($incident)) {
            Auth::user()->incidentsViewed()->save($incident);
        }

        // load the users who haven't viewed the incident
        $unviewed_by = collect([]);
        foreach ($incident->unviewedBy() as $user) {
            foreach ($user->divisions as $division) {
                if (Auth::user()->divisions->contains($division)) {
                    if (!$unviewed_by->contains($user)) {
                        $unviewed_by->push($user);
                    }
                }
            }
        }

    	return view('incidents.show', compact('incident', 'comments', 'photos', 'unviewed_by', 'breadcrumbs'));
    }


    public function create() {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('createIncident'), 'text' => 'Report an Incident'],
        ];

        // collect all the locations
        $locations = Location::orderBy('location', 'ASC')->pluck('location', 'id');

        // collect all the staff
        $staff = User::orderBy('name', 'ASC')->pluck('name', 'id');

    	return view('incidents.create', compact('breadcrumbs', 'locations', 'staff'));
    }


    public function store(Request $request) {
        // validate the request input
        $rules = [
            'date' => 'required',
            'title' => 'required',
            'description' => 'required',
            'user' => 'required',
            'locations' => 'required',
        ];

        $upload_count = count($request->file('patron_photos'));
        foreach(range(0, $upload_count) as $index) {
            $rules['patron_photos.' . $index] = 'image|mimes:jpeg,png,jpg,gif,bmp|max:2048';
        }

        $this->validate($request, $rules);

        // store it in a new instance of Incident
        $incident = new Incident;
        $incident->date = $request->date;
        $incident->time = $request->time;
        $incident->title = $request->title;
        $incident->description = $request->description;
        $incident->user_id = $request->user;
        $incident->patron_name = ($request->patron_name ?: null);
        $incident->card_number = ($request->card_number ?: null);
        $incident->patron_description = ($request->patron_description ?: null);


        // save it to the database, which will give it an id,
        // which is needed for relationships to be created
        if ($incident->save()) {

            // set the location(s) of the incident
            foreach ($request->locations as $location_id) {
                $incident->location()->save(Location::find($location_id));
            }

            // set the users involved in the incident
            if (count($request->usersInvolved)) {
                foreach ($request->usersInvolved as $user_id) {
                    $incident->usersInvolved()->save(User::find($user_id));
                }
            }

            // validate and upload the patron photo if necessary
            if ($request->hasFile('patron_photos')) {

                // loop through the uploads and save them to the filesystem and database
                foreach ($request->file('patron_photos') as $upload) {

                    // create a unique name for the file
                    $filename = uniqid('img_') . '.' . $upload->getClientOriginalExtension();

                    // create a new instance of a photo
                    $photo = Photo::create([
                        'filename' => $filename,
                        'incident_id' => $incident->id,
                    ]);

                    // create the Incident/Photo relationship and move the file
                    if ($incident->photo()->save($photo)) {
                        $upload->move(public_path('images/patrons/'), $filename);
                    }
                }
            }

            // email a notification to all staff
            foreach (User::pluck('email') as $email) {
                \Mail::to($email)->send(new IncidentCreated($incident));
            }

            // redirect back the new incident with a success message
            Session::flash('success_message', "The incident was saved and an email notification was sent to the library staff.");
            return redirect()->route('incident', ['incident' => $incident->id]);
        }
    }


    public function edit(Incident $incident) {
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

            // collect the locations
            $locations = Location::orderBy('location', 'ASC')->pluck('location', 'id');

            // collect all the staff member
            $staff = User::orderBy('name', 'ASC')->pluck('name', 'id');

            return view('incidents.edit', compact('incident', 'photos', 'locations', 'staff', 'breadcrumbs'));
        } else {
            // return to the incident with an error message
            $errors = ['Permission Denied' => 'Only ' .
                                              Auth::user()->find($incident->user_id)->name .
                                              ' or a supervisor can modify this incident. You may comment below.'];
            return view('incidents.show', compact('incident', 'errors', 'breadcrumbs'));
        }
    }


    public function update(Request $request) {
        // validate the form
        $rules = [
            'date' => 'required',
            'title' => 'required',
            'description' => 'required',
            'user' => 'required',
            'locations' => 'required',
        ];
        $this->validate($request, $rules);

        // retrieve the incident from the database
        $incident = Incident::find($request->incident);

        // retrive the parts of the request we need for the incident
        $updates = $request->only(
            'date',
            'time',
            'locations',
            'staffInvolved',
            'patron_name',
            'card_number',
            'patron_description',
            'title',
            'description'
        );
        
        // set each attribute and save to the database
        foreach ($updates as $key => $value) {
            switch ($key) {
                case 'locations':
                    $incident->location()->sync($updates['locations']);
                    break;
                case 'staffInvolved':
                    $incident->usersInvolved()->sync($updates['staffInvolved']);
                    break;
                default:
                    $incident->$key = $value;
                    break;                
            }  
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


    public function search(Request $request) {
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

        // retrieve the incidents which the user has already viewed
        $user_viewed = Auth::user()->incidents;

        // provide the index view with the search results and search string
        return view('incidents.index', compact('incidents', 'search', 'user_viewed'));
    }
}
