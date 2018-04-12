<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectCollection;
use App\Jobs\BackupProject;

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
            'name' => $request->get('project')['name'],
            'urls' => $request->get('project')['url']
        ]);


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
}
