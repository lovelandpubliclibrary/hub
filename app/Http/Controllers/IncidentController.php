<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Incident;
use Auth;
use Session;

class IncidentController extends Controller
{
    public function index()
    {
    	$incidents = Incident::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
    	return view('incidents.index', compact('incidents'));
    }


    public function show(Incident $incident)
    {
        $comments = $incident->comment;
    	return view('incidents.show', compact('incident', 'comments'));
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
        

        // validate and upload the patron photo
        if ($request->hasFile('patron_photo') && $request->file('patron_photo')->isValid()) {
            // create a unique name for the file
            $filename = uniqid('img_') . '.' . $request->patron_photo->getClientOriginalExtension();
            // move the file to the public/images/patrons/ directory
            if ($request->file('patron_photo')->move(public_path('images/patrons/'), $filename)) {
                // save the path to our instance of incident
                $incident->patron_photo = $filename;
            }
        }

        // save it to the database
        if ($incident->save()) {
            Session::flash('success_message', "Incident Saved - \"$incident->title\"");
            return redirect("incidents/$incident->id");
        }
    }


    public function edit(Incident $incident)
    {
        if (Auth::user()->id == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
        {
            return view('incidents.edit', compact('incident'));
        }
        else
        {
            $errors = ['Permission Denied' => 'Only ' . Auth::user()->find($incident->user_id)->name .
                                              ' or a supervisor can modify this incident.'];
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

        $incident = Incident::find($request->incident);
        $updates = $request->only(
            'date',
            'patron_name',
            'card_number',
            'patron_description',
            'title',
            'description'
        );
        
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

        // provide the index view with the search results
        return view('incidents.index', compact('incidents', $request->search));
    }
}
