<?php

use App\Models\VotingSession;
use App\Models\Candidate;
use App\Models\User;
use App\Services\VotingService;
use App\Exceptions\DoubleVoteException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(VotingService::class);
    $this->voter = User::factory()->create(['email_verified_at' => now()]);
    $this->unverifiedVoter = User::factory()->create(['email_verified_at' => null]);
});

it('allows verified user to vote in open session', function () {
    $session = VotingSession::factory()->open()->create();
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    $this->actingAs($this->voter)
        ->post(route('voting.store', $session->slug), [
            'candidate_id' => $candidate->id,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('votes', [
        'voting_session_id' => $session->id,
        'candidate_id' => $candidate->id,
        'user_id' => $this->voter->id,
    ]);
});

it('prevents double voting at the database layer (immutable ledger)', function () {
    $session = VotingSession::factory()->open()->create();
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    // First vote
    $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint');

    // Attempt second vote programmatically to bypass HTTP and hit Service directly
    expect(fn () =>
        $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(DoubleVoteException::class);
});

it('prevents unverified users from voting', function () {
    $session = VotingSession::factory()->open()->create();
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    $this->actingAs($this->unverifiedVoter)
        ->post(route('voting.store', $session->slug), [
            'candidate_id' => $candidate->id,
        ])
        ->assertForbidden();
});

it('prevents voting in sessions that are not active', function () {
    $session = VotingSession::factory()->completed()->create();
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    expect(fn () =>
        $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(\Exception::class, 'Voting session is not open.');
});

it('prevents voting before the window opens', function () {
    $session = VotingSession::factory()->create([
        'status' => VotingSession::STATUS_ACTIVE,
        'start_date' => now()->addDay(),
        'end_date' => now()->addDays(3),
    ]);
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    expect(fn () =>
        $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(\Exception::class, 'Voting session is currently outside the valid time window.');
});

it('rejects a candidate that belongs to a different session', function () {
    $session = VotingSession::factory()->open()->create();
    $otherSession = VotingSession::factory()->open()->create();
    $foreignCandidate = Candidate::factory()->create(['voting_session_id' => $otherSession->id]);

    expect(fn () =>
        $this->service->castVote($session->id, $foreignCandidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(\Exception::class, 'The selected candidate is not part of this voting session.');

    $this->assertDatabaseMissing('votes', ['candidate_id' => $foreignCandidate->id]);
});

it('rejects a cross-session candidate over http before it reaches the service', function () {
    $session = VotingSession::factory()->open()->create();
    $otherSession = VotingSession::factory()->open()->create();
    $foreignCandidate = Candidate::factory()->create(['voting_session_id' => $otherSession->id]);

    $this->actingAs($this->voter)
        ->post(route('voting.store', $session->slug), [
            'candidate_id' => $foreignCandidate->id,
        ])
        ->assertSessionHasErrors('candidate_id');

    $this->assertDatabaseCount('votes', 0);
});

it('hides a draft session from the public booth', function () {
    $session = VotingSession::factory()->create(['status' => VotingSession::STATUS_DRAFT]);

    $this->get(route('public.voting.show', $session->slug))->assertNotFound();
});

it('keeps results private until the session is completed', function () {
    $session = VotingSession::factory()->open()->create([
        'results_visibility' => VotingSession::VISIBILITY_PUBLIC,
    ]);

    expect($session->resultsVisibleTo(null))->toBeFalse();

    $finished = VotingSession::factory()->completed()->create([
        'results_visibility' => VotingSession::VISIBILITY_PUBLIC,
    ]);

    expect($finished->resultsVisibleTo(null))->toBeTrue();
});

it('shows voters_only results only to someone who voted', function () {
    $session = VotingSession::factory()->open()->create([
        'results_visibility' => VotingSession::VISIBILITY_VOTERS_ONLY,
    ]);

    expect($session->resultsVisibleTo($this->voter, hasVoted: false))->toBeFalse();
    expect($session->resultsVisibleTo($this->voter, hasVoted: true))->toBeTrue();
});
