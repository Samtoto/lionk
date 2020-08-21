<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    /**
     * Create common blog
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
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
        $user = Auth::user();
        $blog = new Blog;

        $blog->title = $request->title;

        $blog->content = $request->content;
        $blog->community_id = $request->community;
        $blog->deleted_at = null;
        // $blog->save();
        $user->blog()->save($blog);

        \Debugbar::info('blog saved' . $blog);

        return response()->json($request->input(), 200);
    }

    /**
     * Create blog with image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createImg(Request $request)
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
        $user = Auth::user();
        $blog = new Blog;
        $blog->deleted_at = null;
        $blog->content = null;
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

    /**
     * Get blogs list with relationships (users, community)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        if (Auth::check()) {

            // The follow code is equal to:
            // Get 50 Blogs with count of comments:
            // SELECT `blogs`.*,
            //     (
            //         SELECT COUNT(*)
            //         FROM `comments`
            //         WHERE `blogs`.`id` = `comments`.`blog_id`
            //     ) AS `comment_count`
            // FROM `blogs`
            // LIMIT 50;
            // 
            // Get the relation. blogs <-> users
            // SELECT * FROM `users` WHERE `users`.`id` IN (33, ..., 45);
            // 
            // Get the relation. communities <-> blogs
            // SELECT * FROM `communities` WHERE `communities`.`id` IN (52, ..., 56);
            // 
            // Get the relation. communities <-> current users
            // SELECT `users`.*,
            //     `user_community`.`community_id` AS `pivot_community_id`,
            //     `user_community`.`user_id` AS `pivot_user_id`
            // FROM `users`
            //     INNER JOIN `user_community` ON `users`.`id` = `user_community`.`user_id`
            // WHERE `user_community`.`community_id` IN (52, 56, 58, 59, 61, 63, 65, 76)
            //     and `user_id` = 1

            $blogs = Blog::withTrashed()->limit(50)->with(['user', 'community'=> function ($query) {
                // get the communities  and  its joined status with the request user joined
                $query->with(['user'=> function($query) {
                    $query->where('user_id', Auth::id());
                }]);
            }])->withCount('comment')->get();
        } else {
            $blogs = Blog::withTrashed()->limit(50)->with(['user', 'community' => function ($query) {
                // need user attr
                $query->with(['user' => function ($query) {
                    $query->where('user_id', '0');
                }]);
            }])->withCount('comment')->get();
        }
        foreach ($blogs as $blog) {
            if ($blog->img_path) {
                $blog->img_path = Storage::disk('public_uploads')->url($blog->img_path);
            }
        }

        \Debugbar::info($blogs->toArray());

        return response()->json($blogs, 200);
    }

    /**
     * Show blog by blog_id
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        
        return view('comment.show', ['blog_id' => $request->blog]);
    }

    public function edit(Request $request)
    {
        if (Auth::check()) {

            $blog = Blog::where('id', $request->blog)->with('community')->get();
            if (Gate::allows('edit-blogs', $blog[0])) {
                // $blog->content = base64_decode($blog->content);
                if ($blog[0]->img_path) {
                    $blog[0]->img_path = Storage::disk('public_uploads')->url($blog[0]->img_path);
                }
                \Debugbar::info($blog->toArray());
                // dump($blog);
                return view('blog.edit', ['blog'=> $blog[0]]);
            }
        }
        throw new AuthorizationException;

        // return 'Error on edit blog: ' . $request->blog_id;
    }

    public function destroy(Request $request)
    {
        if (Auth::check()) {

            $blog = Blog::find($request->blog);
            if (Gate::allows('delete-blogs', $blog)) {
                // $blog->content = base64_decode($blog->content);
                $blog->delete();
                \Debugbar::info($blog->toArray());
                // dump($blog);
                return response()->json($blog, 200);
            }
        }
        throw new AuthorizationException;

        // return 'Error on edit blog: ' . $request->blog_id;
    }

    public function update(Request $request) {
        if (Auth::check()) {

            $blog = Blog::find($request->blog);
                // \Debugbar::info($blog->toArray());
            if (Gate::allows('update-blogs', $blog)) {
                // $blog->content = base64_decode($blog->content);
                // \Debugbar::info($blog->toArray());
                // dump($blog);
                if ($blog->img_path) {
                    $validatedData = $request->validate([
                        'title_img' => 'bail|required|max:255',
                        'image' => 'image|required',
                    ]);
                    $blog->title = $request->title_img;
                    $path = $request->file('image')->store('images', 'public_uploads');
                    $blog->img_path = $path;
                    $blog->save();
                    
                } else {
                    $validatedData = $request->validate([
                        'title' => 'bail|required|max:255',
                    ]);
                    $blog->title = $request->title;
                    $blog->content = $request->content;
                    $blog->save();
                }
                
                return response()->json($blog, 200);
            }
        }
        throw new AuthorizationException;
    }
}
