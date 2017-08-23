<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    // model relationships
	public function users() {		// track which users belong to the division
		return $this->belongsToMany('App\User')->withPivot('supervisor')->withTimestamps();
	}
}
