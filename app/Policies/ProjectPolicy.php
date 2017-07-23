<?php

namespace App\Policies;

use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * @param User    $user
     * @param Project $project
     * @return bool
     */
    public function owner(User $user, Project $project)
    {
        return $project->owner_id == $user->id;
    }

    /**
     * @param User    $user
     * @param Project $project
     * @return mixed
     */
    public function member(User $user, Project $project)
    {
        return $project->owner_id == $user->id || $project->members->contains($user);
    }
}
