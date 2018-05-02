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

    public function meta()
    {
        return $this->hasMany('App\ProjectMeta');
    }
    
    public function pings()
    {
        return $this->hasMany('App\Ping');
    }

    public function scopeProfile()
    {
        $metas = $this->meta();

        if($metas->count()){
            $metas = $metas->get()->keyBy('key')->map(function($meta){
                return $meta['value'];
            });

            foreach ($metas as $key => $value) {
                $this->{$key} = $value;
            }

        }

        return $this;

    }

}
