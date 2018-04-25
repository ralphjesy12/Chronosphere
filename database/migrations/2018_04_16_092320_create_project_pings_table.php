<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectPingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_pings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->index();
            $table->string('url')->nullable();
            $table->string('content_type')->nullable();
            $table->string('http_code')->nullable();
            $table->string('header_size')->nullable();
            $table->string('request_size')->nullable();
            $table->string('total_time')->nullable();
            $table->string('namelookup_time')->nullable();
            $table->string('connect_time')->nullable();
            $table->string('pretransfer_time')->nullable();
            $table->string('starttransfer_time')->nullable();
            $table->string('redirect_url')->nullable();
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
        Schema::dropIfExists('project_pings');
    }
}
