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
     * @param Project $model
     * @return bool
     */
    public function owner(User $user, Project $model)
    {
        return $model->owner_id == $user->id;
    }

    /**
     * @param User    $user
     * @param Project $model
     * @return mixed
     */
    public function member(User $user, Project $model)
    {
        return $model->owner_id == $user->id || $model->members->contains($user);
    }
}
