<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePingsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('pings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index();
            $table->string('url')->nullable();
            $table->string('content_type')->nullable();
            $table->integer('http_code')->nullable();
            $table->integer('header_size')->nullable();
            $table->integer('request_size')->nullable();
            $table->integer('filetime')->nullable();
            $table->integer('ssl_verify_result')->nullable();
            $table->integer('redirect_count')->nullable();
            $table->float('total_time',24,12)->nullable();
            $table->float('namelookup_time',24,12)->nullable();
            $table->float('connect_time',24,12)->nullable();
            $table->float('pretransfer_time',24,12)->nullable();
            $table->float('size_upload',24,12)->nullable();
            $table->float('size_download',24,12)->nullable();
            $table->float('speed_download',24,12)->nullable();
            $table->float('speed_upload',24,12)->nullable();
            $table->float('download_content_length')->nullable();
            $table->float('upload_content_length')->nullable();
            $table->float('starttransfer_time',24,12)->nullable();
            $table->float('redirect_time',24,12)->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('primary_ip')->nullable();
            $table->string('certinfo')->nullable();
            $table->integer('primary_port')->nullable();
            $table->string('local_ip')->nullable();
            $table->integer('local_port')->nullable();
            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('pings');
    }
}
