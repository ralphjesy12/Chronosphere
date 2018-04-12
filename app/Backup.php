<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'project_id',
        'status',
        'description',
        'backup_started_at',
        'backup_failed_at',
        'backup_finished_at',
    ];

    protected $dates = [
        'backup_started_at',
        'backup_failed_at',
        'backup_finished_at',
    ];

    public function project(){
        return $this->belongsTo('App\Project');
    }
}
