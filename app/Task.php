<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['uid', 'assigned_to', 'name', 'due_on', 'is_complete'];
}
