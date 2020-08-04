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

        // The follow code is equal to:
        // Get 50 Blogs with count of comments:
        // SELECT `blogs`.*, (
        //     SELECT COUNT(*) FROM `comments` WHERE `blogs`.`id` = `comments`.`blog_id`
        //     ) AS `comment_count` 
        //     FROM `blogs` LIMIT 50;
        // 
        // Get the relation. blogs <-> users
        // SELECT * FROM `users` WHERE `users`.`id` IN (33, ..., 45);
        // 
        // Get the relation. communities <-> blogs
        // SELECT * FROM `communities` WHERE `communities`.`id` IN (52, ..., 56);
        // 
        // Get the relation. communities <-> users
        // SELECT `users`.*, 
        //        `user_community`.`community_id` as `pivot_community_id`, 
        //        `user_community`.`user_id` as `pivot_user_id` 
        //        FROM `users` INNER JOIN 
        //            `user_community` ON `users`.`id` = `user_community`.`user_id` 
        //        WHERE `user_community`.`community_id` in (52, ..., 76);
        $blogs = Blog::limit(50)->with(['user', 'community'=> function ($query) {
            // get the communities  and  its joined status with the request user joined
            $query->with(['user']);
        }])->withCount('comment')->get();

        \Debugbar::info($blogs[0]->toArray());

        return response()->json($blogs, 200);
    }

    public function show (Request $request)
    {
        return view('comment_show', ['blog_id' => $request->blog_id]);
    }
}
