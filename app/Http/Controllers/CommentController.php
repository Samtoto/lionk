<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Blog;

use App\Community;

use Illuminate\Database\Eloquent\Collection;
use Mews\Purifier\Facades\Purifier;

class CommentController extends Controller
{

    /**
     * Get blog by blog_id
     * with relationships of comments and comments' children
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $blog_id = $request->blog_id;
        $blog = Blog::where('id', $blog_id)->with(['comment'=> function($query){
            $query->where('parent_id', null)->with(['allChildren', 'user']);
        }, 'user'])->get();
        return response()->json($blog[0], 200);
    }

    /**
     * Create a comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = $request->user()->id;
        $comment->parent_id = $request->comment_id;
        $comment->content = Purifier::clean($request->comment);
        $comment->blog_id = $request->blog_id;
        $comment->save();
        // TODO should return the success one
        return $this->show($request);
    }

    /**
     * Create a sub comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSub(Request $request)
    {
        $comment = new Comment;
        $comment->user_id = $request->user()->id;
        $comment->parent_id = $request->comment_id;
        $comment->content = Purifier::clean($request->comment);
        $comment->blog_id = $request->blog_id;
        $comment->save();

        // Recursive get comments and children comments
        $comment = Comment::where('id', $comment->id)->with(['allChildren'])->get();

        return response()->json($comment[0], 200);
        // return $this->show($request);
    }
}
