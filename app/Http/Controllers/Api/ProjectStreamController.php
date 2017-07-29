<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Stream;
use App\Project;
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

        $user = User::whereUsername($request->owner_id)->first();

        $stream = Stream::firstOrCreate([
            'name' => $request->name,
            'team_id' => $project->team_id,
        ], [
            'project_id' => $project->id,
            'uid' => generateUid(),
            'owner_id' => $user->id,
        ]);

        $stream->owners()->attach($user->id);

        return $this->response([
            'stream' => $stream->load('owners'),
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
