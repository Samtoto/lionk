<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\User;
use App\Blog;

use App\Community;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

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
        $blog = Blog::withTrashed()->where('id', $blog_id)->with(['comment'=> function($query){
            $query->withTrashed()->where('parent_id', null)->with(['allChildren', 'user']);
        }, 'user'])->get();
        \Debugbar::info($blog[0]->toArray());
        // \Debugbar::info($blog[0]->img_path);


        if ($blog[0]->img_path) {
            $blog[0]->img_path = Storage::disk('public_uploads')->url($blog[0]->img_path);
        }
        // \Debugbar::info($blog[0]->comment);
        return response()->json($blog[0], 200);
    }

    public function index(Request $request)
    {
        $blog_id = $request->blog;
        $comments = Comment::withTrashed()->select(
            ['id', 'content', 'created_at', 'blog_id', 'parent_id', 'user_id', 'deleted_at']
        )->where('blog_id', $blog_id)->with(['user'])->get();
        \Debugbar::info($comments->toArray());
        return response()->json($comments, 200);
    }

    public function edit(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('edit-comments', $comment)) {
                return response()->json($comment, 200);
            }
        }
        throw new AuthorizationException();
    }

    public function update(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('update-comments', $comment)) {
                $validatedData = $request->validate([
                    'content'=> 'required'
                ]);
                $comment->content = $request->content;
                $comment->save();

                return response()->json($comment, 200);
            } 
        }
        throw new AuthorizationException();
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
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->comment_id;
        $comment->content = $request->comment;
        $comment->blog_id = $request->blog_id;
        $comment->save();
        // TODO should return the success one
        return $this->show($request);
    }

    public function delete(Request $request)
    {
        if (Auth::check()) {
            $comment = Comment::find($request->comment_id);
            if (Gate::allows('delete-comments', $comment)) {
                
                $comment->delete();
                $comment = Comment::withTrashed()->find($request->comment_id);
                return response()->json($comment, 200);
            }
        }
        throw new AuthorizationException();
    }

    /**
     * Create a sub comment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSub(Request $request)
    {
        \Debugbar::info($request->input());
        $comment = new Comment;
        $comment->user_id = Auth::id();
        $comment->parent_id = $request->parent_id;
        $comment->content = $request->content;
        $comment->blog_id = $request->blog_id;
        $comment->save();


        // Recursive get comments and children comments
        $comment = Comment::where('id', $comment->id)->with(['allChildren', 'user'])->get();

        \Debugbar::info($comment[0]);
        return response()->json($comment[0], 200);
        // return $this->show($request);
    }
}
