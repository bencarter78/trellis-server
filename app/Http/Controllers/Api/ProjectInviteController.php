<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Jobs\SendInvitation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectInviteController extends Controller
{
    public function store($puid, Request $request)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        $this->dispatch(new SendInvitation($request->email, $project));

        return $this->response();
    }
}
