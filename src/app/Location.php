<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function incident() {
		return $this->belongsToMany('App\Incident')->withTimestamps();
	}
}
