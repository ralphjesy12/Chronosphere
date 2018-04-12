<?php

namespace App\Jobs;

use App\Project;
use App\Backup;
use Storage;
use File;
use Spatie\DbDumper\Databases\MySql;


use Illuminate\Support\Facades\Log;


use Spatie\Backup\Tasks\Backup\BackupJobFactory;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BackupProject implements ShouldQueue
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
        $status = false;
        $errors = [];
        $backups = "backups/" . str_slug($this->project->name);
        $backupsTmp = "backups/tmp";
        $folder = str_random(6);
        $date = now()->toDateTimeString();

        $pathTmp = implode('/',[
            $backupsTmp,
            $folder
        ]);
        $path = implode('/',[
            $backups,
            $date
        ]);

        $session = $this->project->backups()->create();

        // Prepare Temporary Backup Processing Folder
        Storage::makeDirectory($pathTmp);

        $includePaths = [];


        // Check if Project has database

        foreach ($this->project->databases as $key => $database) {

            $errors[] = $this->backupDatabase([
                'host' => $database->host,
                'name' => $database->name,
                'user' => $database->user,
                'pass' => $database->pass,
                'port' => $database->port,
                'socket' => $database->socket
            ],storage_path(implode('/',[
                'app',
                $pathTmp,
                ($key + 1) . '-' . $date  .'.sql'
            ])));

            $includePaths[] = storage_path(implode('/',[
                'app',
                $pathTmp
            ]));

        }

        config([ 'backup.backup.name' => $this->project->name ]);

        // Check if Project has directories
        foreach ($this->project->directories as $key => $directory) {
            $includePaths[] = $directory->path;
        }

        config([ 'backup.backup.source.files.include' => $includePaths ]);

        try {

            $session->update([
                'backup_started_at' => now()
            ]);

            $backupJob = BackupJobFactory::createFromArray(config('backup'));
            $backupJob->disableNotifications();
            $backupJob->dontBackupDatabases();
            $status = $backupJob->run();

            $session->update([
                'status' => 'success',
                'description' => "Backup Successful",
                'backup_finished_at' => now()
            ]);

        } catch (Exception $exception) {

            $session->update([
                'status' => 'failed',
                'description' => "Backup failed because: {$exception->getMessage()}.",
                'backup_failed_at' => now()
            ]);

            return response()->json([
                'status' => 'failed',
                'errors' => [
                    "Backup failed because: {$exception->getMessage()}."
                ]
            ]);
        }
        // Compress the Backup Folder

        // Save a record

        // $status = Storage::move($pathTmp . '/', $path . '/');

        return response()->json([
            'status' => $status,
            'errors' => $errors
        ]);

    }

    public function backupDatabase($config,$path){
        $opts = [
            'dump_binary_path' => '/Applications/AMPPS/mysql/bin'
        ];

        $dump = MySql::create()
        ->setDbName($config['name'])
        ->setUserName($config['user'])
        ->setPassword($config['pass']);

        if(!empty($config['host'])){
            $dump = $dump->setHost($config['host']);
        }

        if(!empty($config['port'])){
            $dump = $dump->setPort($config['port']);
        }

        if(!empty($config['socket'])){
            $dump = $dump->setSocket($config['socket']);
        }

        $dump = $dump
        ->setDumpBinaryPath($opts['dump_binary_path']);

        $dump = $dump->dumpToFile($path);


        return $dump;
    }
}
