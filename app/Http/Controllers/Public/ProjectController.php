<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['category', 'technologies', 'media'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.projects.index', compact('projects'));
    }

    public function show(string $slug)
    {
        $project = Project::with(['category', 'technologies', 'media'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Fetch related projects in same category
        $relatedProjects = Project::with(['category', 'media'])
            ->where('status', 'published')
            ->where('category_id', $project->category_id)
            ->where('id', '!=', $project->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('public.projects.show', compact('project', 'relatedProjects'));
    }
}
