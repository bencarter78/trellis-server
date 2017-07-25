<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TeamRequest;
use App\Team;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->response(['teams' => $this->userFromToken()->teams]);
    }

    /**
     * @param TeamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TeamRequest $request)
    {
        $team = Team::create([
            'uid' => str_random(10),
            'name' => $request->name,
            'owner_id' => JWTAuth::parseToken()->authenticate()->id,
        ]);

        $team->members()->attach($team->owner_id);

        return $this->response(['team' => $team]);
    }

    /**
     * @param $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uid)
    {
        return $this->response([
            'team' => Team::with('projects')->whereUid($uid)->get()->first(),
        ]);
    }
}
