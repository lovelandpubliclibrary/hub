<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    // model relationships
    public function incident() {        // track which incidents the user has authored
        return $this->hasMany('App\Incident');
    }

    public function role() {        // track which roles the user belongs to
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function comment() {         // track which comments the user has authored
        return $this->hasMany('App\Comment');
    }

    public function incidentsViewed() {     // track which incidents the user has viewed
        return $this->belongsToMany('App\Incident', 'incident_user_viewed')->withTimestamps();
    }

    public function incidentsInvolved() {       // track which incidents this user is involved in
        return $this->belongsToMany('App\Incident', 'incident_user_involved')->withTimestamps();
    }

    public function divisions() {        // track which divisions the user is a part of
        return $this->belongsToMany('App\Division')->withTimestamps();
    }

    public function supervisor() {        // track which divisions the user is a part of
        return $this->belongsToMany('App\Division', 'division_user_supervisors')->withTimestamps();
    }
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
