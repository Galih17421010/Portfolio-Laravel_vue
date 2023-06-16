<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Skill;
use Redirect;
use App\Http\Resources\SkillResource;
use Storage;

class SkillController extends Controller
{
    
    public function index()
    {
        $skills = SkillResource::collection(Skill::all());
        return Inertia::render('Skills/index', compact('skills'));
    }

    
    public function create()
    {
        return Inertia::render('Skills/create');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image'],
            'name' => ['required', 'min:3']
        ]);

        if($request->hasFile('image')){
            $image = $request->file('image')->store('skills');
            Skill::create([
                'name' => $request->name,
                'image' => $image,
            ]);

            return Redirect::route('skills.index')->with('message', 'Skill created successfully');
        }
        return Redirect::back();
    }


   
    public function edit(Skill $skill)
    {
        return Inertia::render('Skills/edit', compact('skill'));
    }

    
    public function update(Request $request, Skill $skill)
    {
        $image = $skill->image;
        $request->validate([
            'name' => ['required', 'min:3']
        ]);
        if($request->hasFile('image')){
            Storage::delete($skill->image);
            $image = $request->file('image')->store('skills');
        }

        $skill->update([
            'name' => $request->name,
            'image' => $image
        ]);

        return Redirect::route('skills.index')->with('message', 'Skill updated successfully');

    }

   
    public function destroy(Skill $skill)
    {
        Storage::delete($skill->image);
        $skill->delete();

        return Redirect::back()->with('message', 'Skill deleted successfully');
    }
}
