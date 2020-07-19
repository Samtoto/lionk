<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\User;

class BlogController extends Controller
{
    public function add(Request $request)
    {
        \Debugbar::info($request->input());

        $validatedData = $request->validate([
            // 'title' => 'required|unique:posts|max:255',
            'title' => 'bail|required|max:255',
            'content' => 'required',
            'community' => 'required',
        ]);
        \Debugbar::info('request data validated');
        // return $request->input('title');
        $user = $request->user();
        $blog = new Blog;

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->community_id = $request->community;
        // $blog->save();
        $user->blog()->save($blog);

        \Debugbar::info('blog saved' . $blog);

        return response()->json($request->input(), 200);
    }

    public function show() {
        // $blog = new Blog;
        // $blog->all();
        
        return response()->json(Blog::limit(50)->with(['user'])->withCount('comment')->get(), 200);
        // return response()->json(Blog::limit(50)->with(['user', 'community'])->get(), 200);
    }
}
