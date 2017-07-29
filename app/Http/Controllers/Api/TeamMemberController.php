<?php

namespace App\Http\Controllers\Api;

use App\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $tuid
     * @return \Illuminate\Http\Response
     */
    public function index($tuid)
    {
        $team = Team::whereUid($tuid)->first();

        $this->authorizeForUser($this->userFromToken(), 'member', $team);

        return $this->response(['members' => $team->members]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $tuid
     * @return \Illuminate\Http\Response
     */
    public function show($tuid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $tuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($tuid)
    {
        $team = Team::whereUid($tuid)->first();

        $this->authorizeForUser($this->userFromToken(), 'owner', $team);

        return $this->response();
    }
}
