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

    public function user() {
    	return $this->belongsTo('App\User');
    }

    public function comments() {
        return $this->morphMany('App\Comment', 'commentable');
    }

    // mass assignable attributes
    protected $fillable = ['filename'];

    // return all photos in the specified number of collections (columns)
    static function getColumns(int $numColumns) {
        $numPhotos = Photo::count();
        if ($numPhotos >= 2) {
            $photosPerColumn = intval($numPhotos / $numColumns);
            return Photo::all()->reverse()->chunk($photosPerColumn);
        }
        
        return Photo::all();
    }
}
