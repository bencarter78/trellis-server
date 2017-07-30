<?php

namespace App\Policies;

use App\User;
use App\Milestone;
use Illuminate\Auth\Access\HandlesAuthorization;

class MilestonePolicy
{
    use HandlesAuthorization;

    /**
     * @param User      $user
     * @param Milestone $model
     * @return bool
     */
    public function owner(User $user, Milestone $model)
    {
        return $model->project->owner_id == $user->id;
    }

    /**
     * @param User    $user
     * @param Milestone $model
     * @return mixed
     */
    public function member(User $user, Milestone $model)
    {
        return $model->project->members->contains($user);
    }
}
