<?php

namespace App\Http\Controllers\Api;

use App\Stream;
use App\Project;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Requests\StreamRequest;

class ProjectStreamController extends Controller
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
            'streams' => Stream::where(['project_id' => $project->id])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param               $puid
     * @param StreamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($puid, StreamRequest $request)
    {
        $project = Project::whereUid($puid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $project);

        $stream = Stream::firstOrCreate([
            'name' => $request->name,
            'team_id' => $project->team_id,
        ], [
            'project_id' => $project->id,
            'uid' => str_random(10),
            //            'name' => $request->name,
        ]);

        $stream->owners()->attach($request->owner_id);

        return $this->response([
            'stream' => $stream,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param $puid
     * @param $suid
     * @return \Illuminate\Http\Response
     */
    public function show($puid, $suid)
    {
        $stream = Stream::whereUid($suid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $stream->project);

        return $this->response(['stream' => $stream]);
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
     * @param $suid
     * @return \Illuminate\Http\Response
     */
    public function destroy($puid, $suid)
    {
        $stream = Stream::whereUid($suid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $stream->project);

        $stream->delete();

        return $this->response();
    }
}
