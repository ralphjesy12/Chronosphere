<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Database extends Model
{

    protected $fillable = [
        'project_id',
        'host',
        'name',
        'user',
        'pass',
        'port',
        'socket'
    ];

    public function project(){
        return $this->belongsTo('App\Project');
    }

}
