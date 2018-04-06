<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{

    protected $fillable = [
        'project_id',
        'path'
    ];

    public function project(){
        return $this->belongsTo('App\Project');
    }

}
