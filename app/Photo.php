<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    // model relationships
    public function incident() {
    	return $this->belongsToMany('App\Incident');
    }

    public function patron() {
    	return $this->belongsToMany('App\Patron')->withTimestamps();
    }

    // mass assignable attributes
    protected $fillable = ['filename'];
}
