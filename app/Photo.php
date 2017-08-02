<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    // model relationships
    public function incident() {
    	return $this->belongsTo('App\Incident');
    }

    // mass assignable attributes
    protected $fillable = ['filename'];
}
