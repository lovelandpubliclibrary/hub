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
        return $this->belongsToMany('App\Role');
    }

    public function comment() {
        return $this->hasMany('App\Comment');
    }

    public function incidents() {       // this relationship tracks which incidents the user has viewed
        return $this->belongsToMany('App\Incident');
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
}
