<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
	public function user() {
		return $this->belongsTo('App\User');
	}


	public function truncate_description($length) {
		return substr($this->description, 0, strpos(wordwrap($this->description, $length, '\n'), '\n'));
	}
}
