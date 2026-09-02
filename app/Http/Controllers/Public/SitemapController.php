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

        // The XML declaration is emitted here rather than in the Blade template:
        // the production host has short_open_tag=On, so a literal "<?" anywhere
        // in a Blade file is swallowed by PHP's tokenizer before Blade can
        // compile it. Inside a PHP string it is harmless.
        $content = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .view('public.sitemap', compact('articles', 'events', 'projects', 'votingSessions'))->render();

        return response($content)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
