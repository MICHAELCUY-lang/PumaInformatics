<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\DTOs\ProjectData;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Technology;
use App\Http\Requests\Admin\UpdateProjectRequest;

class ProjectController extends Controller
{
    protected ProjectService $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $projects = $this->service->paginateProjects();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $categories = ProjectCategory::orderBy('order')->get();
        $technologies = Technology::orderBy('name')->get();
        return view('admin.projects.create', compact('categories', 'technologies'));
    }

    public function store(StoreProjectRequest $request)
    {
        $data = ProjectData::fromArray($request->validated());
        $project = $this->service->createProject($data);

        if ($request->hasFile('hero')) {
            $project->addMediaFromRequest('hero')->toMediaCollection('hero');
        }
        
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $project->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $categories = ProjectCategory::orderBy('order')->get();
        $technologies = Technology::orderBy('name')->get();
        return view('admin.projects.edit', compact('project', 'categories', 'technologies'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $data = ProjectData::fromArray($request->validated());
        $this->service->updateProject($project->id, $data);

        if ($request->hasFile('hero')) {
            $project->addMediaFromRequest('hero')->toMediaCollection('hero');
        }
        
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $project->addMedia($image)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->service->deleteProject($project->id);
        return redirect()->route('admin.projects.index')->with('success', 'Project removed.');
    }
}
