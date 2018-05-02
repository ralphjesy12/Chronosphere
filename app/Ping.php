<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
    protected $fillable = [
        'project_id' ,
        'url' ,
        'content_type' ,
        'http_code' ,
        'header_size' ,
        'request_size' ,
        'filetime' ,
        'ssl_verify_result' ,
        'redirect_count' ,
        'total_time' ,
        'namelookup_time' ,
        'connect_time' ,
        'pretransfer_time' ,
        'size_upload' ,
        'size_download' ,
        'speed_download' ,
        'speed_upload' ,
        'download_content_length' ,
        'upload_content_length' ,
        'starttransfer_time' ,
        'redirect_time' ,
        'redirect_url' ,
        'primary_ip' ,
        'certinfo' ,
        'primary_port' ,
        'local_ip' ,
        'local_port'
    ];

    protected $casts = [
        'certinfo' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
