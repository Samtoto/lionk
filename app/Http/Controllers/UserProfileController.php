<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Blog;
use App\Comment;
use App\User;

class UserProfileController extends Controller
{
    public function config(Request $request) {
        // 
        // $blog = new Blog;
        // dump($blog->all());

        // save comment with relation
        // $blog = Blog::find(1);
        // $comment = new Comment;
        // $comment->content = 'comment content 1-3';
        // dump($blog->comment()->save($comment));
        // return $comment;
        // done

        // get all blogs which has comment
        // $blog = new Blog;
        // $res = $blog->has('comment')->get();
        // done

        // get id=1 blog, and all its comment, remember select id & select blog_id
        // $post = Blog::select('id', 'title', 'content')->with(
        //     ['comment'=> function($query) {
        //         $query->select('blog_id', 'content');
        //     }]
        // )->find(1);
        // dump($post);
        // // dump('1');
        // return $post;
        // done

        // insert into blog with relation as user
        // $user = User::find($request->user()['id']); // equal to:
        // $request->user();

        // $user = $request->user();
        // $blog = new Blog;
        // $blog->title = 'title 1';
        // $blog->content = 'content 1';
        // $user->blog()->save($blog);
        // done

        // get all blogs with user relation
        $blogs = Blog::with('user')->get();
        return $blogs;
    }

    public function show(Request $request)
    {
        return response()->json(Auth::user(), 200);
    }
}
