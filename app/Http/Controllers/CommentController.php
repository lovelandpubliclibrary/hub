<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Session;
use Auth;

class CommentController extends Controller
{
    // ensure user is authenticated in order to use this controller
    public function __construct() {
        $this->middleware('auth');
    }

    public function store(Request $request) {
    	// validate the request input
        $rules = [
            'comment' => 'required',
            'user' => 'required',
            'incident' => 'integer',
            'patron' => 'integer'
        ];
        $this->validate($request, $rules);

        // store it in a new instance of Incident
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user_id = $request->user;

        if(isset($request->incident)) {
            $comment->incident_id = $request->incident;
        }
        
        if(isset($request->patron)) {
            $comment->patron_id = $request->patron;
        }

        // save it to the database
        if ($comment->save()) {
            Session::flash('success_message', "Comment Saved.");
            return redirect()->route('incident', ['incident' => $comment->incident_id]);
        }
    }


    public function edit(Comment $comment)
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => route('incidents'), 'text' => 'Incidents'],
            ['link' => route('incident', ['incident' => $comment->incident->id]), 'text' => $comment->incident->title],
        ];

        // make sure the user has permission to edit the comment
        if (Auth::id() == $comment->user_id)
        {
            return view('comments.edit', compact('comment', 'breadcrumbs'));
        }
        else
        {
            // return to the incident with an error message
            $errors = ['Permission Denied' => 'Only ' .
                                              Auth::user()->find($comment->user_id)->name .
                                              ' can modify this comment.'];
            $incident = $comment->incident;
            return view('incidents.show', compact('incident', 'errors', 'breadcrumbs'));
        }
    }


    public function update(Request $request)
    {
        // validate the form
        $rules = [
            'comment' => 'required',
            'user' => 'required'
        ];
        $this->validate($request, $rules);

        // retrieve the comment from the database
        $comment = Comment::find($request->comment_id);
        
        // update the comment
        $comment->comment = $request->comment;

        // save the updates to the database
        $comment->save();

        Session::flash('success_message', "Comment Updated.");
        return redirect()->route('incident', ['incident' => $comment->incident->id]);
    }


    public function delete(Comment $comment) {
        $incident_id = $comment->incident->id;
        $comment->delete();
        Session::flash('success_message', 'Comment deleted.');
        return redirect()->route('incident', ['incident' => $incident_id]);
    }
}
