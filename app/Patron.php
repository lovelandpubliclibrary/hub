<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    // model relationships
    public function incident() {
    	return $this->belongsToMany('App\Incident')->withTimestamps();
    }

    public function photo() {
    	return $this->belongsToMany('App\Photo')->withTimestamps();
    }


    // get and return the patron name or patron number if no name is available
    public function get_full_name() {
    	if (isset($this->first_name) || isset($this->last_name)) {
            // provide placeholder value for any missing names
            if ($this->first_name || $this->last_name) {
                $this->first_name = $this->first_name ?: '(Unknown first name)';
                $this->last_name = $this->last_name ?: '(Unknown last name)';
            } else {

            }
            
    		return "{$this->first_name} {$this->last_name}";
    	}

    	return "Patron #{$this->id}";
    }


    // return patron name in "LastName, FirstName" format
    public function get_list_name() {
        if ($this->first_name || $this->last_name) {    // make sure at least one name is set

            // provide placeholder value for any missing names
            $this->first_name = $this->first_name ?: '(Unknown first name)';
            $this->last_name = $this->last_name ?: '(Unknown last name)';

            return "{$this->last_name}, {$this->first_name}";
        }

        // both first and last names are unknown, return patron number
        return null;
    }

}
