<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    // model relationships
	public function users() {		// track which users belong to the division
		return $this->belongsToMany('App\User')->withTimestamps();
	}

	public function supervisors() {		// track which users are supervisors for a division
		return $this->belongsToMany('App\User', 'division_user_supervisors')->withTimestamps();
	}
}
