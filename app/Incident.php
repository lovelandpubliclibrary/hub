<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
	// model relationships
	public function user() {
		return $this->belongsTo('App\User');
	}

	public function comment() {
		return $this->hasMany('App\Comment');
	}


	// truncate the description field of incidents for summary display
	public function truncate_description($length) {
		return substr($this->description, 0, strpos(wordwrap($this->description, $length, '\n'), '\n'));
	}
}
