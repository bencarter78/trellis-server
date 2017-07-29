<?php

namespace App\Policies;

use App\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function member(User $user, Team $team)
    {
        return $team->members->contains($user);
    }

    /**
     * @param User $user
     * @param Team $team
     * @return mixed
     */
    public function owner(User $user, Team $team)
    {
        return $team->owner_id == $user->id;
    }
}
