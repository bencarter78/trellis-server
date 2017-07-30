<?php

namespace App\Policies;

use App\User;
use App\Objective;
use Illuminate\Auth\Access\HandlesAuthorization;

class ObjectivePolicy
{
    use HandlesAuthorization;

    /**
     * @param User      $user
     * @param Objective $model
     * @return bool
     */
    public function owner(User $user, Objective $model)
    {
        return $model->project->owner_id == $user->id;
    }

    /**
     * @param User    $user
     * @param Objective $model
     * @return mixed
     */
    public function member(User $user, Objective $model)
    {
        return $model->project->members->contains($user);
    }
}
