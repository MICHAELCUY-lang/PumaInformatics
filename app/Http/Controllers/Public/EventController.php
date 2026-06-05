<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with(['category', 'media', 'tags'])
            ->where('status', 'published')
            ->orderBy('start_date', 'asc')
            ->paginate(12);

        return view('public.events.index', compact('events'));
    }

    public function show(string $slug)
    {
        $event = Event::with(['category', 'media', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Fetch upcoming events
        $upcomingEvents = Event::with(['category', 'media'])
            ->where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('public.events.show', compact('event', 'upcomingEvents'));
    }
}
