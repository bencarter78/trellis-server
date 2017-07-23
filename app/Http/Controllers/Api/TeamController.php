<?php

namespace App\Http\Controllers\Api;

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
        $user = JWTAuth::parseToken()->authenticate();

        return $this->response(['teams' => $user->teams]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->name) {
            $team = Team::create([
                'uid' => str_random(10),
                'name' => $request->name,
                'owner_id' => JWTAuth::parseToken()->authenticate()->id,
            ]);

            $team->members()->attach($team->owner_id);

            return $this->response([
                'team' => $team,
            ]);
        }

        return $this->responseError([
            'title' => 'Invalid data',
            'detail' => 'Please enter a name for your team.',
        ], 400);
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
