<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VotingSessionService;
use App\Repositories\Contracts\VotingSessionRepositoryInterface;
use App\Http\Requests\Admin\StoreVotingSessionRequest;
use App\Http\Requests\Admin\UpdateVotingSessionRequest;
use App\DTOs\VotingSessionData;
use App\Models\VotingSession;

class VotingSessionController extends Controller
{
    public function __construct(
        protected VotingSessionService $votingSessionService,
        protected VotingSessionRepositoryInterface $votingSessionRepository
    ) {}

    public function index(Request $request)
    {
        $this->authorize('manage.voting');
        
        $filters = $request->only(['search', 'status']);
        $sessions = $this->votingSessionRepository->paginateWithCounts(15, $filters);
        
        return view('admin.voting-sessions.index', compact('sessions', 'filters'));
    }

    public function create()
    {
        $this->authorize('manage.voting');
        return view('admin.voting-sessions.create');
    }

    public function store(StoreVotingSessionRequest $request)
    {
        $sessionData = VotingSessionData::fromArray($request->validated());
        
        $this->votingSessionService->createSession($sessionData);
        
        return redirect()->route('admin.voting-sessions.index')
            ->with('success', 'Voting session created successfully.');
    }

    public function show(VotingSession $voting_session)
    {
        $this->authorize('manage.voting');
        $session = $this->votingSessionRepository->findWithCandidates($voting_session->id);
        
        // Calculate winners and analytics
        $totalVotes = $session->votes_count;
        $candidates = $session->candidates;
        
        // Ensure candidates are sorted by vote count desc for the results page
        $rankedCandidates = $candidates->sortByDesc('votes_count')->values();
        
        return view('admin.voting-sessions.show', compact('session', 'rankedCandidates', 'totalVotes'));
    }

    public function edit(VotingSession $voting_session)
    {
        $this->authorize('manage.voting');
        return view('admin.voting-sessions.edit', compact('voting_session'));
    }

    public function update(UpdateVotingSessionRequest $request, VotingSession $voting_session)
    {
        $sessionData = VotingSessionData::fromArray($request->validated());
        
        $this->votingSessionService->updateSession($voting_session->id, $sessionData);
        
        return redirect()->route('admin.voting-sessions.index')
            ->with('success', 'Voting session updated successfully.');
    }

    public function destroy(VotingSession $voting_session)
    {
        $this->authorize('manage.voting');
        
        try {
            $this->votingSessionService->deleteSession($voting_session->id);
            return redirect()->route('admin.voting-sessions.index')
                ->with('success', 'Voting session deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
