<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;

class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {

        return view('home');

        $session = str_random(6);

        Storage::makeDirectory('backups/' . $session);

        $opts = [
            'dump_binary_path' => '/Applications/AMPPS/mysql/bin'
        ];

        $config = [
            'files' => [
                'include' => [
                    '/Applications/AMPPS/www/pecp'
                ],
                'exclude' => [

                ]
            ],
            'databases' => [
                [
                    'type' => 'mysql',
                    'name' => 'phinma',
                    'user' => 'root',
                    'pass' => 'root',
                    'socket' => '/Applications/AMPPS/var/mysql.sock',
                ]
            ]
        ];

        foreach ($config['databases'] as $key => $db) {
            switch ($db['type']) {
                case 'mysql':

                $mysql = MySql::create()
                ->setDbName($db['name'])
                ->setUserName($db['user'])
                ->setPassword($db['pass'])
                ->setDumpBinaryPath($opts['dump_binary_path'])
                ->dumpToFile(storage_path('app/backups/'.$session.'/dump_'.$db['name'].' '. date('Y-m-d') .'.sql'));

                break;

                default:

                break;
            }
        }

    }
}
