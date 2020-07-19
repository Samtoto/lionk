<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Blog;

use App\Community;

use Illuminate\Database\Eloquent\Collection;

class CommentController extends Controller
{
    public function show(Request $request)
    {
        // dump($request->blog_id);
        
        return view('comment_show', ['blog_id' => $request->blog_id]);

        // return __CLASS__ . __FUNCTION__ . ' on line 14';

        // get all relation comment child with recursive
        // $comment = new Comment;
        
        // $blogs = Blog::where('id', $request->id)
        //                 ->with(['comment'=> function($query) {
        //                     $query->where('parent_id', null)->with('allChildren');
        //                 }])
        //                 ->get();
        // // dump( response()->json($blogs[0], 200) );

        // $comment = 0;
        // dump(response()->json(Comment::where('id', 5)->with('allChildren')->get()->toArray(), 200));
        // done

        // save a comment with relation
        // $comment->content = 'test comment content';
        // $comment->user_id = $user->id;

        // $blog->comment()->save($comment);
        // done

        // get all comments belong to blog.first
        // return $blog->comment()->get();
        // done

        // get all user's comment
        // return $user->comment()->get();
        // done
    }

    public function showComment(Request $request)
    {
        $blog_id = $request->blog_id;
        $blog = Blog::where('id', $blog_id)->with(['comment'=> function($query){
            $query->where('parent_id', null)->with(['allChildren', 'user']);
        }, 'user'])->get();
        return response()->json($blog[0], 200);
    }

    public function add(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = $request->user()->id;
        $comment->parent_id = $request->comment_id;
        $comment->content = $request->comment;
        $comment->blog_id = $request->blog_id;
        $comment->save();
        return $this->showComment($request);
    }

    // public function add(Request $request)
    // {
        
    // }
}
