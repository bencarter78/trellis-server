<?php

namespace App\Http\Controllers\Api;

use App\Team;
use App\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response(['projects' => Project::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param                           $tuid
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($tuid, Request $request)
    {
        $team = Team::whereUid($tuid)->first();

        $user = $this->userFromToken();

        $this->authorizeForUser($user, 'member', $team);

        if ($request->has('name')) {
            $project = Project::create([
                'uid' => generateUid(),
                'team_id' => $team->id,
                'owner_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
                'due_on' => $request->due_on,
            ]);

            $project->members()->attach($user->id);

            return $this->response([
                'project' => $project,
            ]);
        }

        return $this->responseError([
            'title' => 'Invalid data',
            'detail' => 'Please enter a name for your team.',
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param $tuid
     * @param $puid
     * @return \Illuminate\Http\Response
     */
    public function show($tuid, $puid)
    {
        $project = Project::with('members', 'milestones', 'objectives', 'owner', 'streams', 'streams.owners', 'tasks', 'team')
                          ->whereUid($puid)
                          ->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $project);

        return $this->response(['project' => $project]);
    }
}
