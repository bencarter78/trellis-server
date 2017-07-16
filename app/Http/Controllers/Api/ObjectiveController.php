<?php

namespace App\Http\Controllers\Api;

use App\Project;
use App\Objective;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $project = Project::whereUid($id)->first();

        return $this->response([
            'objectives' => Objective::where(['project_id' => $project->id])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param                           $id
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::whereUid($id)->first();

        if ($project->owner_id != $user->id) {
            return abort(403, 'Only the project owner can create objectives');
        }

        if ($request->name) {
            return $this->response([
                'objective' => Objective::create([
                    'project_id' => $project->id,
                    'uid' => str_random(10),
                    'name' => $request->name,
                ]),
            ]);
        }

        return $this->responseError([
            'title' => 'Invalid data',
            'detail' => 'Please enter the name of the objective.',
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $oid)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::whereUid($id)->first();

        if ($project->owner_id != $user->id) {
            return abort(403, 'Only the project owner can delete objectives');
        }

        Objective::findOrFail($oid)->delete();

        return $this->response();
    }
}
