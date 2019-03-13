<?php

namespace App;

use App\Incident;
use App\Photo;
use App\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
Use Carbon;

class User extends Authenticatable
{
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

    public function reportsTo() {
        return $this->belongsTo('App\User', 'supervisor_id');
    }

    public function supervises() {
        return $this->hasMany('App\User', 'supervisor_id');
    }

    public function patrons() {
        return $this->hasMany('App\Patron');
    }

    public function photos() {
        return $this->hasMany('App\Photo');
    }


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

        // return the unviewed incidents
        return $incidents->diff($viewed_after_cutoff);
    }


    /**
     * Determine if a user has a given role
     * 
     * @return boolean
     */
    private function hasRole(Role $role) {
        return $this->role->contains($role) ?: false;
    }


    /**
     * Determine if the user is an admin
     * @return boolean
     */
    public function isAdministrator() {
        $admin_role = Role::where('role', 'Administrator')->get()->first();
        return $this->hasRole($admin_role);
    }


    /**
     * Determine if the user is a supervisor
     * @return boolean
     */
    public function isSupervisor() {
        $supervior_role = Role::where('role', 'Supervisor')->get()->first();
        return $this->hasRole($supervior_role);
    }


    /**
     * Determine if the user is the Director
     * @return boolean
     */
    public function isDirector() {
        $director_role = Role::where('role', 'Director')->get()->first();
        return $this->hasRole($director_role);
    }


    /**
     * Retrieve all the users in the same divisions as the current user
     * 
     * @return \Collection Collection of users in the same divisions
     */
    public function usersInDivisions() {
        $coworkers = collect();

        foreach ($this->divisions as $division) {
            $coworkers = $coworkers->merge($division->users);
        }

        return $coworkers;
    }
}
