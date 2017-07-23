<?php

namespace App\Http\Controllers\Api;

use App\Team;
use App\Stream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamStreamSearchController extends Controller
{
    /**
     * @param         $uid Team uid
     * @param Request $request
     * @return mixed
     */
    public function index($uid, Request $request)
    {
        $team = Team::where('uid', $uid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $team);

        return $this->response([
            'streams' => Stream::where('team_id', $team->id)->where('name', 'LIKE', "%$request->q%")->get(),
        ]);
    }
}
