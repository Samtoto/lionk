<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Community extends Model
{
    public function user()
    {
        // this->belongstomany->use('App\UserCommunity');
        return $this->belongsToMany('App\User', 'user_community', 'community_id', 'user_id');
    }

    public function blog()
    {
        return $this->hasMany('App\Blog');
    }
}
