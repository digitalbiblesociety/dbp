<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Models\User\Project;
use App\Models\User\ProjectMember;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::whereHas('admins', function ($query) {
            $query->where('user_id', Auth::user()->id);
        })->withCount('members')->get();

        return view('dashboard.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project = Project::where('id', $request->id)->first();
        return view('dashboard.projects.edit', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::where('id', $id)->first();
        return view('dashboard.projects.edit', compact('project'));
    }

    /**
     * Show existing members for a project
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function members($id)
    {
        $project = Project::where('id', $id)->first();
        $members = ProjectMember::where('project_id', $project->id)->paginate();
        return view('dashboard.projects.members', compact('project', 'members'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project = Project::where('id', $id)->first();
        return view('dashboard.projects.edit', compact('project'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::where('id', $id)->first();
        return view('dashboard.projects.edit', compact('project'));
    }
}
