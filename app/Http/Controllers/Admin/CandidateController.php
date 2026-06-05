<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CandidateService;
use App\Repositories\Contracts\CandidateRepositoryInterface;
use App\Repositories\Contracts\VotingSessionRepositoryInterface;
use App\Http\Requests\Admin\StoreCandidateRequest;
use App\Http\Requests\Admin\UpdateCandidateRequest;
use App\DTOs\CandidateData;
use App\Models\Candidate;
use App\Models\VotingSession;

class CandidateController extends Controller
{
    public function __construct(
        protected CandidateService $candidateService,
        protected CandidateRepositoryInterface $candidateRepository,
        protected VotingSessionRepositoryInterface $votingSessionRepository
    ) {}

    public function create(Request $request)
    {
        $this->authorize('manage.voting');
        
        $sessionId = $request->query('session');
        $sessions = $this->votingSessionRepository->all();
        
        return view('admin.candidates.create', compact('sessions', 'sessionId'));
    }

    public function store(StoreCandidateRequest $request)
    {
        $candidateData = CandidateData::fromArray($request->validated());
        
        $this->candidateService->createCandidate($candidateData);
        
        return redirect()->route('admin.voting-sessions.show', $candidateData->voting_session_id)
            ->with('success', 'Candidate created successfully.');
    }

    public function edit(Candidate $candidate)
    {
        $this->authorize('manage.voting');
        $sessions = $this->votingSessionRepository->all();
        return view('admin.candidates.edit', compact('candidate', 'sessions'));
    }

    public function update(UpdateCandidateRequest $request, Candidate $candidate)
    {
        $candidateData = CandidateData::fromArray($request->validated());
        
        $this->candidateService->updateCandidate($candidate->id, $candidateData);
        
        return redirect()->route('admin.voting-sessions.show', $candidateData->voting_session_id)
            ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Candidate $candidate)
    {
        $this->authorize('manage.voting');
        
        $sessionId = $candidate->voting_session_id;
        
        try {
            $this->candidateService->deleteCandidate($candidate->id);
            return redirect()->route('admin.voting-sessions.show', $sessionId)
                ->with('success', 'Candidate deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
