<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Incident;
use App\Role;
Use Carbon;

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
        return $this->belongsToMany('App\Division')->withPivot('supervisor')->withTimestamps();
    }

    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Find all the incidents that the user hasn't viewed after a specified date
     * 
     * @return \Collection Collection of incidents which have not be viewed by the user
     */
    public function unviewedIncidents() {

        $cutoff_date = $this->created_at->subMonth();       // determine how far back users are responsible for viewing incidents
        
        // retrieve all the incidents that occurred after the cutoff date
        $incidents = Incident::where('date', '>=', $cutoff_date->toDateString())->get();

        // retrieve all the incidents that the user has viewed after the cutoff date
        $viewed_after_cutoff = $this->incidentsViewed()->where('date', '>=', $cutoff_date->toDateString())->get();

        /*$viewed_after_cutoff = Incident::whereHas('usersViewed', function ($query) use ($cutoff_date) {
            $query->where('users.created_at', '>=', $cutoff_date);
        })->get();*/
        
        //dd($cutoff_date, $incidents->count(), $viewed_after_cutoff->count());

        // return the unviewed incidents
        return $incidents->diff($viewed_after_cutoff);
    }


    public function hasRole(Role $role) {
        return $this->role->contains($role) ? true : false;
    }
}
