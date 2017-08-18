<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
	// model relationships
	public function user() {		// this relationship tracks which user authored the incident
		return $this->belongsTo('App\User');
	}

	public function comment() {
		return $this->hasMany('App\Comment');
	}

	public function photo() {
		return $this->hasMany('App\Photo');
	}
	
	public function location() {
		return $this->belongsToMany('App\Location')->withTimestamps();
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
}
