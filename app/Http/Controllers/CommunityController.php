<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Community;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request)
    {
        $community_id = $request->community_id;
        $user = Auth::user();

        $community = Community::find($community_id);
        // Join or unjoin the community
        $community->user()->toggle($user);

        // return response()->json(['join_status'=>'changed'], 200);
        return $this->all($request);
    }

    /**
     * Get all communities which user has joined
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
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

}
