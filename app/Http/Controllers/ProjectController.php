<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Skill;
use App\Models\Project;
use Redirect;
use App\Http\Resources\ProjectResource;
use Storage;



class ProjectController extends Controller
{
    
    public function index()
    {
        $projects = ProjectResource::collection(Project::with('skill')->get());
        return Inertia::render('Projects/index', compact('projects'));
    }

    
    public function create()
    {
        $skills = Skill::all();
        return Inertia::render('Projects/create', compact('skills'));

    }

    
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image'],
            'name' => ['required', 'min:3'],
            'skill_id' => ['required'],
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image')->store('projects');
            Project::create([
                'skill_id' => $request->skill_id,
                'name' => $request->name,
                'image' => $image,
                'project_url' => $request->project_url,
            ]);

            return Redirect::route('projects.index')->with('message', 'Project created successfully');
        }
        return Redirect::back();
    }


   
    public function edit(Project $project)
    {
        $skills = Skill::all();
        return Inertia::render('Projects/edit', compact('project', 'skills'));
    }

    
    public function update(Request $request, Project $project)
    {
        $image = $project->image;
        $request->validate([
            'name' => ['required', 'min:3'],
            'skill_id' => ['required'], 
        ]);
        if($request->hasFile('image')){
            Storage::delete($project->image);
            $image = $request->file('image')->store('projects');
        }

        $project->update([
            'name' => $request->name,
            'skill_id' => $request->skill_id,
            'project_url' => $request->project_url,
            'image' => $image,
        ]);
        return Redirect::route('projects.index')->with('message', 'Project updated successfully');
    }

    
    public function destroy(Project $project)
    {
        Storage::delete($project->image);
        $project->delete();

        return Redirect::back()->with('message', 'Project deleted successfully');
    }
}
