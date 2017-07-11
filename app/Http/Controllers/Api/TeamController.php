<?php

namespace App\Http\Controllers\Api;

use App\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeamController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->name) {
            return $this->response([
                'ok' => true,
                'team' => Team::create([
                    'uid' => strtolower(str_random(7) . Carbon::now()->timestamp),
                    'name' => $request->name,
                    'owner_id' => JWTAuth::parseToken()->authenticate()->id,
                ]),
            ]);
        }

        return $this->responseError([
            'title' => 'Invalid data',
            'detail' => 'Please enter a name for your team.',
        ], 400);
    }

    public function show($uid)
    {
        return $this->response([
            'ok' => true,
            'team' => Team::with('projects')->whereUid($uid)->get()->first(),
        ]);
    }
}
