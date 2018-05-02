<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Project;
use Validator;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;
use Log;

class PingWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $project;

    public $timeout = 0;
    public $tries = 1;

    /**
    * Create a new job instance.
    *
    * @return void
    */
    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        if(!empty($url = $this->project->profile()->url)){

            $validator = Validator::make([
                'url' => $url
            ], [
                'url' => 'required|url',
            ]);

            if ($validator->fails()) {
                // Do something when validation fails

                Log::error('Invalid URL : ' . $url);
            }else{

                $latest = $this->project->pings()->latest()->first();

                if(!$latest || $latest->created_at->diffInMinutes(now()) > 5){

                    $_this = $this;
                    $client = new Client();
                    $request = new Request('HEAD', $url);

                    $ping = $_this->project->pings()->create();

                    $response = $client->send($request, [
                        'timeout' => 30,
                        'verify' => false,
                        'on_stats' => function (TransferStats $stats) use($_this,$url,$ping){

                            $stats_data = $stats->getHandlerStats();

                            if(is_array($stats_data)){
                                foreach ($stats_data as $key => $value) {
                                    if(in_array($key,[
                                        'total_time',
                                        'namelookup_time',
                                        'pretransfer_time',
                                        'size_upload',
                                        'size_download',
                                        'speed_download',
                                        'starttransfer_time',
                                        ])){
                                            $stats_data[$key] = (float)$value;
                                        }
                                    }


                                    $ping->update($stats_data);
                                }

                                // You must check if a response was received before using the
                                // response object.
                                if ($stats->hasResponse()) {

                                    Log::error(json_encode([
                                        'type' => 'ping',
                                        'url' => $url,
                                        'status' => $stats->getResponse()->getStatusCode()
                                    ]));

                                } else {
                                    // Error data is handler specific. You will need to know what
                                    // type of error data your handler uses before using this
                                    // value.

                                    Log::error(json_encode($stats->getHandlerErrorData()));

                                }
                            }
                        ]);

                    }

                }

            }
        }
    }
