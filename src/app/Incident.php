<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Incident extends Model
{
	// model relationships
	public function user() {		// author
		return $this->belongsTo('App\User');
	}

	public function comments() {		
		return $this->morphMany('App\Comment', 'commentable');
	}

	public function photo() {
		return $this->belongsToMany('App\Photo');
	}
	
	public function location() {
		return $this->belongsToMany('App\Location')->withTimestamps();
	}

	public function patron() {
		return $this->belongsToMany('App\Patron')->withTimestamps();
	}

	// track which users have viewed the incident
	public function usersViewed() {
		return $this->belongsToMany('App\User', 'incident_user_viewed')->withTimestamps();
	}

	// track which users were involved in the incident
	public function usersInvolved() {
		return $this->belongsToMany('App\User', 'incident_user_involved')->withTimestamps();
	}

	// truncate the description field of incidents for summary display
	public function truncate_description($length) {
		return substr($this->description, 0, strpos(wordwrap($this->description, $length, '\n'), '\n'));
	}

	// return all the users which haven't viewed the incident
	public function unviewedBy() {
		$users = User::all();
		return $users->diff($this->usersViewed);
    }
}
