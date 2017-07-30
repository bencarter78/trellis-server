<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Objective;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectiveRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = 'App\\'.ucfirst($request->resource);

        $resource = (new $model())->whereUid($request->uid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $resource);

        return $this->response(['tasks' => $resource->tasks]);
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
