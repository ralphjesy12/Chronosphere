<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use GuzzleHttp\TransferStats;

class PingWebsite extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'ping:website {url} {--timeout=10}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command description';

    /**
    * Create a new command instance.
    *
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
    }

    /**
    * Execute the console command.
    *
    * @return mixed
    */
    public function handle()
    {
        $url = $this->argument('url');
        $timeout = $this->option('timeout');

        if(empty($url)){
            $this->error('Please provide a URL to ping');
        }

        $_this = $this;
        $client = new Client();
        $request = new Request('HEAD', $url);
        $response = $client->send($request, [
            'timeout' => $timeout,
            'verify' => false,
            'on_stats' => function (TransferStats $stats) use($_this){
                echo $stats->getEffectiveUri() . "\n";
                echo $stats->getTransferTime() . "\n";
                // var_dump($stats->getHandlerStats());
                $stats_data = $stats->getHandlerStats();

                array_walk($stats_data, function(&$value, $key) use (&$stats_data){
                        $stats_data[$key] = [ $key , json_encode($value)];
                });

                $_this->table(['key','value'], $stats_data);

                // You must check if a response was received before using the
                // response object.
                if ($stats->hasResponse()) {
                    echo $stats->getResponse()->getStatusCode();
                } else {
                    // Error data is handler specific. You will need to know what
                    // type of error data your handler uses before using this
                    // value.
                    var_dump($stats->getHandlerErrorData());
                }
            }
        ]);

        $this->info($response->getStatusCode());
        $this->info($response->getReasonPhrase());
        $this->info($response->getProtocolVersion());

    }
}
