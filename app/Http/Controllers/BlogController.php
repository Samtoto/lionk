<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\User;
use Mews\Purifier\Facades\Purifier;

class BlogController extends Controller
{
    public function add(Request $request)
    {
        \Debugbar::info($request->input());

        $validatedData = $request->validate([
            // 'title' => 'required|unique:posts|max:255',
            'title' => 'bail|required|max:255',
            // 'content' => 'required',
            'community' => 'required',
        ]);
        \Debugbar::info('request data validated');
        // return $request->input('title');
        $user = $request->user();
        $blog = new Blog;

        $blog->title = $request->title;

        $blog->content = Purifier::clean($request->content);
        $blog->community_id = $request->community;
        // $blog->save();
        $user->blog()->save($blog);

        \Debugbar::info('blog saved' . $blog);

        return response()->json($request->input(), 200);
    }

    /**
     * Add img tab
     *
     * @param Request $request
     * @return json
     */
    public function addImg(Request $request)
    {
        \Debugbar::info($request->input());

        $validatedData = $request->validate([
            // 'title' => 'required|unique:posts|max:255',
            'title_img' => 'bail|required|max:255',
            // 'content' => 'required',
            'image' => 'image',
            'community' => 'required',
        ]);
        \Debugbar::info('request data validated');
        // return $request->input('title');
        $user = $request->user();
        $blog = new Blog;

        $blog->title = $request->title_img;

        // $blog->content = Purifier::clean($request->content);
        $path = $request->file('image')->store('images', 'public_uploads');
        \Debugbar::info($path);
        /* get img src */
        // $filePath = Storage::disk('public_uploads')->url($path);
        // \Debugbar::info($filePath);

        $blog->img_path = $path;

        $blog->community_id = $request->community;
        // $blog->save();
        $user->blog()->save($blog);

        \Debugbar::info('blog saved' . $blog);

        return response()->json($request->input(), 200);
    }

    public function all(Request $request) {
        $user = $request->user();
        // get all blogs with users and communities
        // return:
        // ["id" => 13
        //   "title" => "test Community"
        //   "content" => "test Community add blog"
        //   "user_id" => 1
        //   "community_id" => 52
        //   "comment_count" => 5
        //   "user" => array:6 []
        //   "community" => array:6 [
        //     "id" => 52
        //     "name" => "Futurology"
        //     "user" => array:1 [
        //       0 => array:7 [
        //         "id" => 1
        //         "name" => "Sam"
        //         "pivot" => array:2 [
        //           "community_id" => 52
        //           "user_id" => 1
        //         ]
        //       ]
        //     ]
        //   ]
        // ]
        $blogs = Blog::limit(50)->with(['user', 'community'=> function ($query) use ($user) {
            // get the communities  and  its joined status with the request user joined
            $query->with(['user' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }]);
        }])->withCount('comment')->get();

        \Debugbar::info($blogs[0]->toArray());

        return response()->json($blogs, 200);
    }

    public function show (Request $request)
    {
        return view('comment_show', ['blog_id' => $request->blog_id]);
    }
}
