<?php

namespace App\Http\Controllers\Api;

use App\Project;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class UserProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('team')
            ->where('owner_id', JWTAuth::parseToken()->authenticate()->id)
            ->get();
            
        return $this->response([
            'ok' => true,
            'projects' => $projects
        ]);
    }
}
