<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // model relationships
    public function incident() {
    	return $this->belongsTo('App\Incident');
    }

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function patron() {
    	return $this->belongsTo('App\Patron');
    }
}
