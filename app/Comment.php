<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Environment;

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

    public function getByBlogId($blog_id)
    {
        $comments = Comment::select(
            ['id', 'content', 'created_at', 'blog_id', 'parent_id', 'user_id']
        )->where('blog_id', $blog_id)->with(['user'])->get();

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $commonMark = new CommonMarkConverter(
            ['html_input' => 'strip', 'allow_unsafe_links' => false],
            $environment
        );

        foreach ($comments as $comment) {
            $comment->content = $commonMark->convertToHtml($comment->content);
        }
        return $comments;
    }
}
