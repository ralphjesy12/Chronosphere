<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    protected $fillable = [
        'name',
        'urls'
    ];

    protected $casts = [
        'urls' => 'object'
    ];

    public function directories(){
        return $this->hasMany('App\Directory');
    }
    public function databases(){
        return $this->hasMany('App\Database');
    }
    public function backups(){
        return $this->hasMany('App\Backup');
    }

}
