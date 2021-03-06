<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function blog()
    {
        return $this->hasMany('App\Blog');
    }

    // public function comment()
    // {
    //     return $this->hasManyThrough(
    //         'App\Blog', 
    //         'App\Comment',
    //         'user_id',
    //         'blog_id',
    //         'id',
    //         'id'
    //     );
    // }

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    public function community()
    {
        return $this->belongsToMany('App\Community', 'user_community', 'user_id', 'community_id');
    }
}



