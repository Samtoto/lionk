<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Community;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe(Request $request)
    {
        $community_id = $request->community;
        $user = Auth::user();

        $community = Community::find($community_id);
        // Join or unjoin the community
        $community->user()->toggle($user);
        $community = Community::where('id', $community_id)->with(['user' => function ($query) {
            $query->where('user_id', Auth::id())->exists();
        }])->get()[0];
        if ($community->user->isNotEmpty()) {
            $community->joined = true;
        } else {
            $community->joined = false;
        }
        unset($community->user);
        \Debugbar::info($community->toArray());

        return response()->json($community, 200);
        // return response()->json(['join_status'=>'changed'], 200);

    }

    /**
     * Get all communities which user has joined
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // get all communities
        // and with user relation
        $communities = Community::with(['user'=> function ($query) {
            $query->where('user_id', Auth::id());
        }])->get();


        foreach ($communities as $community) {
            $community->joined = false;

            if ($community->user->isNotEmpty()) {
                // \Debugbar::info($community->user->isEmpty());
                $community->joined = true;
            }

            unset($community->user);
        }
        \Debugbar::info($communities->toArray());
        // dump($communities->toJson(JSON_PRETTY_PRINT));
        return response()->json($communities, 200);
    }

    public function my()
    {
        $user = Auth::user();
        $myCommunities = $user->with('community')->get()[0]->community;
        return response()->json($myCommunities, 200);
        // dump($myCommunities->toJson(JSON_PRETTY_PRINT));
        // dump($user->toJson(JSON_PRETTY_PRINT));
    }

    public function show(Request $request)
    {
        return view('community');
    }

    public function create(Request $request)
    {
        if (Auth::check()) {
            if (Gate::allows('create-community')) {

                $request->validate([
                    'name' => ['required', 'max:255', 'unique:communities'],
                    'description' => ['required'],
                    'avatar' => 'nullable|image',
                    'banner' => 'nullable|image',
                    'theme_color' => ['max:6'],
                ]);

                $community = new Community;

                if ($request->file('avatar')) {
                    $avatar_path = $request->file('avatar')->store('images', 'public_uploads');
                    $community->avatar_path = $avatar_path;
                }
                if ($request->file('banner')) {
                    $banner_path = $request->file('banner')->store('images', 'public_uploads');
                    $community->banner_path = $banner_path;
                }

                $community->name = $request->name;
                $community->description = $request->description;
                $community->theme_color = $request->theme_color;
                $community->save();
                \Debugbar::info($community->toArray());
                return response()->json($community, 200);
            }
        }
    }

}
