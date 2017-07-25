<?php

namespace App\Http\Controllers\Api;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectMemberController extends Controller
{
    public function index($puid)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $project);

        return $this->response(['members' => $project->members]);
    }


    public function store($puid, Request $request)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        if ($request->has('user_id')) {
            $project->members()->attach($request->user_id);
            $project->team->members()->attach($request->user_id);
        }

        return $this->response(['members' => $project->fresh()->members]);
    }

    public function destroy($puid, $uid)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        $project->members()->detach($uid);

        return $this->response();
    }
}
