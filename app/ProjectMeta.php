<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectMeta extends Model
{
    protected $fillable = [
        'project_id', 'key', 'value'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
