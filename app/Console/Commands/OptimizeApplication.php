<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeApplication extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'optimize:app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clearing all application cache data including route, view, config';

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
        $this->call('route:clear');
        $this->call('view:clear');
        $this->call('config:clear');
        $this->call('cache:clear');
        $this->call('queue:flush');
        $this->call('clear-compiled');
        $this->call('package:discover');
    }
}
