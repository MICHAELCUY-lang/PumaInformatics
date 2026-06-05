<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventTag;
use App\Http\Requests\Admin\StoreEventTagRequest;

class EventTagController extends Controller
{
    public function index()
    {
        // Simple implementation for now. Usually handled via AJAX.
        return response()->json(EventTag::all());
    }

    public function store(StoreEventTagRequest $request)
    {
        EventTag::create($request->validated());
        return redirect()->back()->with('success', 'Tag created.');
    }
}
