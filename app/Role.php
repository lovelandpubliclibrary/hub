<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	// model relationships
    public function user() {
    	return $this->belongsToMany('App\User');
    }
}
