<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class division extends Model
{
    // model relationships
	public function user() {		// track which users belong to the division
		return $this->belongsToMany('App\User')->withTimestamps();
	}
}
