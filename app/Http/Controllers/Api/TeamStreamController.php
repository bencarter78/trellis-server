<?php

namespace App\Http\Controllers\Api;

use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamStreamController extends Controller
{
    /**
     * @param $uid
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($uid)
    {
        return $this->response(['streams' => Team::whereUid($uid)->first()->streams]);
    }
}
