<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Session;

class CommentController extends Controller
{
    public function store(Request $request) {
    	// validate the request input
        $rules = [
            'comment' => 'required',
            'user' => 'required',
            'incident' => 'required'
        ];
        $this->validate($request, $rules);

        // store it in a new instance of Incident
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user_id = $request->user;
        $comment->incident_id = $request->incident;

        // save it to the database
        if ($comment->save()) {
            Session::flash('success_message', "Comment Saved.");
            return redirect()->route('incident', ['incident' => $comment->incident_id]);
        }
    }
}
