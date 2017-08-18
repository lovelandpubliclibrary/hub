<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    // model relationships
    public function incident() {        // this relations tracks which incidents the user has authored
        return $this->hasMany('App\Incident');
    }

    public function role() {
        return $this->belongsToMany('App\Role')->withTimestamps();
    }

    public function comment() {
        return $this->hasMany('App\Comment');
    }

    // this relationship tracks which incidents the user has viewed
    public function incidentsViewed() {
        return $this->belongsToMany('App\Incident', 'incident_user_viewed')->withTimestamps();
    }

    // track which incidents this user is involved in
    public function incidentsInvolved() {
        return $this->belongsToMany('App\Incident', 'incident_user_involved')->withTimestamps();
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
