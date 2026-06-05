<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Event;
use App\Models\Project;
use App\Models\VotingSession;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $articles = Article::where('status', 'published')->where('published_at', '<=', now())->get();
        $events = Event::where('status', 'published')->get();
        $projects = Project::where('status', 'published')->get();
        
        // Voting sessions (assuming we want them indexed if active or completed)
        $votingSessions = VotingSession::whereIn('status', ['active', 'completed'])->get();

        $content = view('public.sitemap', compact('articles', 'events', 'projects', 'votingSessions'))->render();

        return response($content)->header('Content-Type', 'text/xml');
    }
}
