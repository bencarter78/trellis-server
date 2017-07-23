<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['project_id', 'uid', 'name', 'due_on'];

    /**
     * @var array
     */
    protected $dates = ['due_on'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
