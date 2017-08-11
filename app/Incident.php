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

	public function users() {		// this relationship track which users have viewed the incident
		return $this->belongsToMany('App\User');
	}


	// truncate the description field of incidents for summary display
	public function truncate_description($length) {
		return substr($this->description, 0, strpos(wordwrap($this->description, $length, '\n'), '\n'));
	}
}
