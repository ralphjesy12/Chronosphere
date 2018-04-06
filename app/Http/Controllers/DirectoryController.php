<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Connectors\MySqlConnector;
use Storage;

class DirectoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getPath($folder = 'root')
    {
        switch ($folder) {
            case 'root':
            default:
            $path = base_path();
            break;
        }

        return response()->json([
            'path' => $path
        ]);
    }
    public function checkPath(Request $request)
    {

        $exists = false;
        $directories = 0;
        $files = 0;
        $path = '';
        $name = 'Untitled Folder';

        $this->validate($request,[
            'path' => 'required|string'
        ]);

        $exists = Storage::disk('root')->exists($request->get('path'));

        if($exists){
            $path = pathinfo($request->get('path'));
            $base = $path['basename'];
            $path = $path['dirname'];
            if($base != ''){
                $name = $base;
            }
            $directories = count(Storage::disk('root')->directories($path . '/' . $base));
            $files = count(Storage::disk('root')->files($path . '/' . $base));
        }

        return response()->json([
            'status' => $exists,
            'path' => $path,
            'name' => $name,
            'directories' => $directories,
            'files' => $files
        ]);
    }
    public function checkDatabase(Request $request)
    {
        $active = false;

        $this->validate($request,[
            'db' => 'required|string'
        ]);

        $db = json_decode($request->get('db'),true);

        $connection = false;

        try{
            $connection = new MySqlConnector;

            $result = $connection->connect([
                'driver' => 'mysql',
                'host' => $db['host'],
                'port' => $db['port'],
                'database' => $db['name'],
                'username' => $db['user'],
                'password' => $db['pass'],
                'unix_socket' => $db['socket'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);
            
            $query = $result->prepare('SELECT 1');

            $active = true;
        }catch(Exception $e){
            $active = false;
            $connection = false;

            return response()->json([
                'status' => $active,
                'connection' => $connection,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => $active,
            'connection' => $connection
        ]);
    }
}
