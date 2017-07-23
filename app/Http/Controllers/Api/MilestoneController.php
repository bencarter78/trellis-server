<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Milestone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MilestoneRequest;

class MilestoneController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param                  $puid
     * @param MilestoneRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($puid, MilestoneRequest $request)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        return $this->response([
            'milestone' => Milestone::create([
                'project_id' => $project->id,
                'uid' => str_random(10),
                'name' => $request->name,
                'description' => $request->description,
                'due_on' => $request->due_on,
            ]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $puid
     * @param $muid
     * @return \Illuminate\Http\Response
     */
    public function show($puid, $muid)
    {
        $milestone = Milestone::whereUid($muid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $milestone->project);

        return $this->response(['milestone' => $milestone]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $puid
     * @param $muid
     * @return \Illuminate\Http\Response
     */
    public function destroy($puid, $muid)
    {
        $milestone = Milestone::whereUid($muid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $milestone->project);

        return $this->response();
    }
}
