<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\TaskRequest;
use App\Project;
use App\Objective;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ObjectiveRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = 'App\\' . ucfirst($request->resource);

        $resource = (new $model)->whereUid($request->uid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $resource);

        return $this->response(['tasks' => $resource->tasks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $model = 'App\\' . ucfirst($request->resource);

        $resource = (new $model())->whereUid($request->uid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $resource);

        return $this->response([
            'task' => $resource->tasks()->create([
                'uid' => generateUid(),
                'assigned_to' => $request->assigned_to,
                'name' => $request->name,
                'due_on' => Carbon::createFromFormat('d/m/Y', $request->due_on),
            ]),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $tuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($tuid)
    {
        $task = Task::whereUid($tuid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $task->owner);

        $task->delete();

        return $this->response();
    }
}
