<?php

namespace App\Policies;

use App\User;
use App\Stream;
use Illuminate\Auth\Access\HandlesAuthorization;

class StreamPolicy
{
    use HandlesAuthorization;

    /**
     * @param User      $user
     * @param Stream $model
     * @return bool
     */
    public function owner(User $user, Stream $model)
    {
        return $model->owners->last()->id == $user->id || $model->project->owner_id == $user->id;
    }

    /**
     * @param User    $user
     * @param Stream $model
     * @return mixed
     */
    public function member(User $user, Stream $model)
    {
        return $model->project->members->contains($user);
    }
}
