<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\ActivityLogger;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::forUser(auth()->user())
            ->withCount('tasks')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $this->authorize('create', Project::class);
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $project = $request->user()->projects()->create($request->validated());

        ActivityLogger::log(
            actor: $request->user(),
            subject: $project,
            action: 'created',
            projectId: $project->id,
            meta: ['project_name' => $project->name],
        );

        return redirect()->route('projects.show', $project)
            ->with('success', "Project \"{$project->name}\" created.");
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $taskCounts = $project->tasks()
            ->selectRaw('state, count(*) as total')
            ->groupBy('state')
            ->pluck('total', 'state');

        $activity = $project->activityEvents()
            ->with('actor')
            ->take(20)   // ← bumped from 15 to 20
            ->get();

        return view('projects.show', compact('project', 'taskCounts', 'activity'));
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $oldName = $project->name;
        $project->update($request->validated());

        ActivityLogger::log(
            actor: $request->user(),
            subject: $project,
            action: 'updated',
            projectId: $project->id,
            meta: ['old_name' => $oldName, 'new_name' => $project->name],
        );

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $name = $project->name;

        ActivityLogger::log(
            actor: auth()->user(),
            subject: $project,
            action: 'deleted',
            projectId: $project->id,
            meta: ['project_name' => $name],
        );

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', "Project \"{$name}\" deleted.");
    }
}
