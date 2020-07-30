<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Community;

class CommunityController extends Controller
{
    /**
     * Undocumented function
     *
     * @param Request $request
     * @return string json
     */
    public function changeStatus(Request $request)
    {
        $community_id = $request->community_id;
        $user = $request->user();

        $community = Community::find($community_id);

        $community->user()->toggle($user);

        // return response()->json(['join_status'=>'changed'], 200);
        return $this->all($request);
    }

    public function all(Request $request)
    {
        $user = $request->user();
        // get all communities
        // and with user relation
        $communities = Community::with(['user'=> function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();
        
        \Debugbar::info($communities->toArray());
        // dump($communities->toJson(JSON_PRETTY_PRINT));
        return response()->json($communities, 200);
    }

    public function my(Request $request)
    {
        $user = $request->user();
        $myCommunities = User::where('id', $user->id)->with('community')->get()[0]->community;
        return response()->json($myCommunities, 200);
        // dump($myCommunities->toJson(JSON_PRETTY_PRINT));
        // dump($user->toJson(JSON_PRETTY_PRINT));
    }

}
