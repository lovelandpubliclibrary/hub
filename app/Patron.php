<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 */
class Patron extends Model
{
    // model relationships
    public function incident() {
    	return $this->belongsToMany('App\Incident')->withTimestamps();
    }

    public function photo() {
    	return $this->belongsToMany('App\Photo')->withTimestamps();
    }

    public function user() {
        return $this->belongsTo('App\User');
    }


    /**
     * Returns an appropriately formatted patron name.
     * 
     * @param  mixed $format Specifies the format of the name to return.
     * @param  string $replacement This value will replace any missing names.
     * 
     * @return string The formatted patron name.
     */
    public function get_name($format = 'list', $replacement = 'Unknown') {
        if ($this->first_name || $this->last_name) {    // make sure at least one name is set

            // provide placeholder value for any missing names
            $this->first_name = $this->first_name ?: $replacement;
            $this->last_name = $this->last_name ?: $replacement;

            switch($format) {
                case 'list':
                    return "{$this->last_name}, {$this->first_name}";
                    break;

                case 'full':
                    return "{$this->first_name} {$this->last_name}";
                    break;

                case 'heading':
                    if ($this->last_name === $replacement) {
                        return "Patron #{$this->id}: {$this->first_name}";
                        break;
                    }

                    return "Patron #{$this->id}: {$this->last_name}";
                    break;

                default:
                    return $this->get_name('list', $replacement);
                    break;
            }
        }

        // both first and last names are unknown, return patron number
        return "Patron #{$this->id}";
    }

}
