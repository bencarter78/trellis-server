<?php

namespace App;

use App\User;
use App\Team;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['owner_id', 'team_id', 'uid', 'name', 'description'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'owner_id');
    }
}
