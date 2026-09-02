<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\CabinetDepartment;
use App\Models\CabinetMember;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index(Request $request)
    {
        // Oldest first, matching the homepage strip: the row reads as a timeline.
        $cabinets = Cabinet::orderBy('term_year', 'asc')->get();

        // Resolve active cabinet: from query param, or the one marked active, or the latest
        $activeCabinet = null;
        if ($request->has('cabinet')) {
            $activeCabinet = Cabinet::where('slug', $request->query('cabinet'))->first();
        }
        if (!$activeCabinet) {
            $activeCabinet = $cabinets->firstWhere('is_active', true) ?? $cabinets->first();
        }

        // Departments belong to a cabinet, so each generation shows the structure
        // it actually ran rather than a merged list. Departments with no members
        // are dropped so an incomplete archive does not render empty sections.
        $departments = CabinetDepartment::query()
            ->when($activeCabinet, fn ($q) => $q->where('cabinet_id', $activeCabinet->id))
            ->with(['members' => function ($query) use ($activeCabinet) {
                $query->where('is_active', true)
                      ->orderBy('role_hierarchy_level', 'asc')
                      ->with('media');
                if ($activeCabinet) {
                    $query->where('cabinet_id', $activeCabinet->id);
                }
            }])
            ->orderBy('order', 'asc')
            ->get()
            ->filter(fn ($department) => $department->members->isNotEmpty())
            ->values();

        // Fetch Executive members (those without a department)
        $executives = CabinetMember::whereNull('department_id')
            ->where('is_active', true)
            ->with('media')
            ->orderBy('role_hierarchy_level', 'asc');
        
        if ($activeCabinet) {
            $executives->where('cabinet_id', $activeCabinet->id);
        }
        
        $executives = $executives->get();

        // Each generation's own programme of events, newest first.
        $events = \App\Models\Event::query()
            ->where('status', 'published')
            ->when($activeCabinet, fn ($q) => $q->where('cabinet_id', $activeCabinet->id))
            ->with('media')
            ->orderBy('start_date', 'desc')
            ->get();

        return view('public.cabinet.index', compact(
            'departments', 'cabinets', 'activeCabinet', 'executives', 'events'
        ));
    }

    public function show($slug)
    {
        $member = CabinetMember::where('slug', $slug)
            ->with(['department', 'cabinet'])
            ->firstOrFail();

        return view('public.cabinet.show', compact('member'));
    }
}

