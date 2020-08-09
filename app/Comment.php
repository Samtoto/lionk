<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $fillable = ['title'];

    public function blog()
    {
        return $this->belongsTo('App\Blog', 'blog_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Comment', 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
        // can also:
        // return $this->hasMany('App\Comment', 'parent_id'); 
    }

    // public function allChildren()
    // {
    //     return $this->child()->child();
    // }


    public function allChildren()
    {
        return $this->child()->with(['allChildren', 'user']);
    }

    // public function comments()
    // {
    //     return $this->hasMany('Comment', 'parent_id');
    // }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
