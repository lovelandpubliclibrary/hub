<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
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

    public function store(StoreComment $request) {
        // gather the comment info
        $comment = new Comment([
            'comment' => $request->comment,
            'user_id' => $request->user,
            'commentable_type' => $request->commentable['type'],
            'commentable_id' => $request->commentable['id']
        ]);

        $comment->save();

        Session::flash('success_message', "Comment Saved.");
        return redirect()->route(
            $comment->commentable_type,
            ['id' => $comment->commentable_id]
        );
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


    public function update(StoreComment $request)
    {
        // retrieve the comment from the database
        $comment = Comment::find($request->comment_id);
        
        // update the comment
        $comment->comment = $request->comment;

        // save the updates to the database
        $comment->save();

        Session::flash('success_message', "Comment Updated.");
        return redirect()->route($comment->commentable_type, ['id' => $comment->commentable_id]);
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
