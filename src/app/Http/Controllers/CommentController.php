<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use Session;
use Auth;
use App\Incident;
use App\Patron;

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
            'incident' => 'integer|nullable',
            'patron' => 'integer|nullable',
            'source' => 'string|required',
            'source_id' => 'integer|required',
        ];
        $this->validate($request, $rules);


        // store it in a new instance of Incident
        $comment = new Comment;
        $comment->comment = $request->comment;
        $comment->user_id = $request->user;

        // save so that we can attach relationships to the comment
        if ($comment->save()) {

            $source = $request->source;
            $source_id = $request->source_id;

            switch ($source) {
                case 'patron':
                    $patron = Patron::find($source_id);
                    $comment->patron()->associate($patron)->save();
                    break;
                case 'incident':
                    $incident = Incident::find($source_id);
                    $comment->incident()->associate($incident)->save();
                    break;
            }

            Session::flash('success_message', "Comment Saved.");
            return redirect()->route($source, [$source => $source_id]);
        }
    }


    public function edit(Comment $comment, Request $request)
    {
        // set up the breadcrumbs for this action
        $breadcrumbs = [
            ['link' => route('home'), 'text' => 'Home'],
            ['link' => $request->url(), 'text' => 'Comments'],
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
            return view(' .show', compact('incident', 'errors', 'breadcrumbs'));
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

        // determine the where the comment appears
        $source = [];
        if ($incident = $comment->incident) {
            $source['source'] = 'incident';
            $source['id'] = $incident->id;
        } else if ($patron = $comment->patron) {
            $source['source'] = 'patron';
            $source['id'] = $patron->id;
        }

        Session::flash('success_message', "Comment Updated.");
        return redirect()->route($source['source'], [$source['source'] => $source['id']]);
    }


    public function delete(Comment $comment) {
        // determine where to redirect back to
        $redirect = '/';
        if ($patron = $comment->patron) {
            $redirect = "/patrons/{$patron->id}";
        } else if ($incident = $comment->incident) {
            $redirect = "incidents/{$incident}";
        }

        $comment->delete();

        Session::flash('success_message', 'Comment deleted.');
        return redirect($redirect);
    }
}
