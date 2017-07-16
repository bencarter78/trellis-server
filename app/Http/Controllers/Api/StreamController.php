<?php

namespace App\Http\Controllers\Api;

use App\Stream;
use App\Project;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class StreamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uid)
    {
        $project = Project::whereUid($uid)->first();

        return $this->response([
            'streams' => Stream::where(['project_id' => $project->id])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::whereUid($id)->first();

        if ($project->owner_id != $user->id) {
            return abort(403, 'Only the project owner can create streams');
        }

        if ($request->name) {
            return $this->response([
                'objective' => Stream::create([
                    'project_id' => $project->id,
                    'owner_id' => $request->has('owner_id') ? $request->owner_id : $user->id,
                    'uid' => str_random(10),
                    'name' => $request->name,
                ]),
            ]);
        }

        return $this->responseError([
            'title' => 'Invalid data',
            'detail' => 'Please enter the name of the stream.',
        ], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($pid, $sid)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $project = Project::whereUid($pid)->first();

        if ($project->owner_id != $user->id) {
            return abort(403, 'Only the project owner can delete streams');
        }

        Stream::findOrFail($sid)->delete();

        return $this->response();
    }
}
