<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectCollection;
use App\Jobs\BackupProject;

use Artisan;

class ProjectController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return new ProjectCollection(Project::all());
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request,[
            'project.name' => 'required|unique:projects,name'
        ]);

        $project = Project::create([
            'name' => $request->get('project')['name']
        ]);


        if($urls = $request->get('project')['url']){

            foreach ($urls as $key => $url) {
                if(empty(trim($url))) continue;
                $project->meta()->updateOrCreate([
                    'key' => 'url'
                ],[
                    'value' => $url
                ]);
            }

        }


        if($request->has('directories')){
            foreach ($request->get('directories') as $key => $directory) {
                $project->directories()->create([
                    'path' => implode('/',[
                        $directory['path'],
                        $directory['name']
                    ])
                ]);
            }
        }

        if($request->has('database') && count($request->get('database')) > 0){
            $project->databases()->create([
                'host' => $request->get('database')['host'],
                'name' => $request->get('database')['name'],
                'user' => $request->get('database')['user'],
                'pass' => $request->get('database')['pass'],
                'port' => $request->get('database')['port'],
                'socket' => $request->get('database')['socket']
            ]);
        }

        return response()->json([
            'project' => $project,
            'request' => $request->all()
        ]);

    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function show(Project $project)
    {
        return response()->json([
            'project' => $project->load([ 'directories' , 'backups' , 'databases' ])
        ]);
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function edit(Project $project)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'status' => 'Project deleted successfully!'
        ]);
    }

    public function backup(Project $project)
    {
        dispatch(new BackupProject($project));

        return response()->json([
            'status' => 'Project Backup queued successfully!'
        ]);
    }

    public function apiGetPing(Request $request,Project $project){

        $from = now()->subHour(1);
        $to = now();

        $data = [];

        $startOfDay = today();

        $pings = $project->pings()->oldest()->where([
            [ 'created_at' , '>' , $from ],
            [ 'created_at' , '<' , $to ],
            [ 'http_code' , '=' , 200 ],
            [ 'total_time' , '>' , 0]
        ])->get();

        $goFrom = $startOfDay->diffInMinutes($from);
        $goTo = $startOfDay->diffInMinutes($to);

        $goFrom -= $goFrom%10;

        for ($i = $goFrom; $i <= $goTo; $i = $i+10) {

            $time = today()->addMinute($i);

            $data[$i] = [
                'time' => $time,
                'timestamp' => $time->timestamp,
                'connect_time' => 0,
                'namelookup_time' => 0,
                'pretransfer_time' => 0,
                'starttransfer_time' => 0,
                'total_time' => 0,
            ];

        }

        foreach ($pings as $key => $ping) {

            $minuteOfTheDay = $startOfDay->diffInMinutes($ping->created_at);

            $minuteOfTheDay -= $minuteOfTheDay%10;

            $time = today()->addMinute($minuteOfTheDay);

            $data[$minuteOfTheDay] = [
                'time' => $time,
                'timestamp' => $time->timestamp,
                'connect_time' => $ping->connect_time,
                'namelookup_time' => $ping->namelookup_time,
                'pretransfer_time' => $ping->pretransfer_time,
                'starttransfer_time' => $ping->starttransfer_time,
                'total_time' => $ping->total_time,
            ];

        }

        return response()->json([
            'data' => $data,
        ]);
    }
}
