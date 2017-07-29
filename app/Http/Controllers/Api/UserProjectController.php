<?php

namespace App\Http\Controllers\Api;

use App\Project;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class UserProjectController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->response([
            'projects' => Project::with('team')->where('owner_id', $this->userFromToken()->id)->get(),
        ]);
    }
}
