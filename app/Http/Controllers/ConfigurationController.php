<?php

namespace App\Http\Controllers;

use App\Models\FarmRequirementLarge;
use App\Models\FarmRequirement;


use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Checklist')) {
            $checklists = FarmRequirement::get();

            return view('configurations.index', compact('checklists'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }



    public function create()
    {
        if (\Auth::user()->can('Create Checklist')) {

            return view('configurations.create_chapters');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

  

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Checklist')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:100',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $department             = new Chapters();
            $department->name       = $request->name;
            $department->code       = $request->code;

            $department->created_by = \Auth::user()->creatorId();
            $department->save();

            return redirect()->route('chapters.index')->with('success', __('Department  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    

    public function show(Chapters $chapter)
    {
        return redirect()->route('configuratinos.index');
    }

    public function edit(Chapters $chapter)
    {
        if (\Auth::user()->can('Edit Checklist')) {

                return view('configurations.edit_chapters', compact('chapter'));
           
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }
    

    public function update(Request $request, Chapters $chapter)
    {
        if (\Auth::user()->can('Edit Chapters')) {
            if ($department->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:20',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $department->name      = $request->name;
                $department->save();

                return redirect()->route('configurations.index')->with('success', __('Department successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

        public function destroy($id)
    {
        if (\Auth::user()->can('Delete Chapters')) {
            $chapter = Chapters::findOrFail($id);
   
            $chapter->delete();
            return redirect()->route('chapters.index')->with('success', __('Chapter successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
  
}
