<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EventService;
use App\Http\Requests\Admin\StoreEventRequest;
use App\DTOs\EventData;
use App\Models\Event;

class EventController extends Controller
{
    protected EventService $service;

    public function __construct(EventService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $events = $this->service->paginateEvents();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = EventData::fromArray($request->validated());
        $event = $this->service->createEvent($data);

        if ($request->hasFile('featured_image')) {
            $event->addMediaFromRequest('featured_image')->toMediaCollection('hero');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $event->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        // We'll reuse StoreEventRequest since validation is basically the same
        $data = EventData::fromArray($request->validated());
        $event = $this->service->updateEvent($event->id, $data);

        if ($request->hasFile('featured_image')) {
            $event->clearMediaCollection('hero');
            $event->addMediaFromRequest('featured_image')->toMediaCollection('hero');
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $event->addMedia($file)->toMediaCollection('gallery');
            }
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->service->deleteEvent($event->id);
        return redirect()->route('admin.events.index')->with('success', 'Event removed.');
    }
}
