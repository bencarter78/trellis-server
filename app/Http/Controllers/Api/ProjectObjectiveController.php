<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Objective;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectiveRequest;

use Carbon\Carbon;

class ProjectObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $puid
     * @return \Illuminate\Http\Response
     */
    public function index($puid)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $project);

        return $this->response([
            'objectives' => Objective::where(['project_id' => $project->id])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param                  $puid
     * @param ObjectiveRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($puid, ObjectiveRequest $request)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        return $this->response([
            'objective' => Objective::create([
                'project_id' => $project->id,
                'uid' => generateUid(),
                'name' => $request->name,
                'due_on' => Carbon::createFromFormat('d/m/Y', $request->due_on),
            ]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $puid
     * @return \Illuminate\Http\Response
     */
    public function destroy($puid, $ouid)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        Objective::whereUid($ouid)->first()->delete();

        return $this->response();
    }
}
