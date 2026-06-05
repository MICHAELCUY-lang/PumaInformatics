<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AspirationService;
use App\Models\Aspiration;

class AspirationController extends Controller
{
    protected AspirationService $service;

    public function __construct(AspirationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category_id']);
        $aspirations = $this->service->paginateAspirations($filters);
        return view('admin.aspirations.index', compact('aspirations'));
    }

    public function show(Aspiration $aspiration)
    {
        $aspiration->load(['category', 'user', 'media']);
        return view('admin.aspirations.show', compact('aspiration'));
    }

    public function update(Request $request, Aspiration $aspiration)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,under_review,responded,resolved,archived,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $this->service->updateStatus($aspiration->id, $validated['status'], $validated['admin_notes'] ?? null);

        return redirect()->route('admin.aspirations.show', $aspiration)->with('success', 'Aspiration status updated.');
    }
}
