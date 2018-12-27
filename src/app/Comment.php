<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // make all fields mass assignable
    protected $guarded = [];


    // model relationships
    /**
     * Get all of the owning commentable models.
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
