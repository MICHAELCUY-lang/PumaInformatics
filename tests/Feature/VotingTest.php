<?php

use App\Models\VotingSession;
use App\Models\Candidate;
use App\Models\User;
use App\Services\VotingService;
use App\Exceptions\DoubleVoteException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(VotingService::class);
    $this->voter = User::factory()->create(['email_verified_at' => now()]);
    $this->unverifiedVoter = User::factory()->create(['email_verified_at' => null]);
});

it('allows verified user to vote in open session', function () {
    $session = VotingSession::factory()->create([
        'status' => 'open',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
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
    $session = VotingSession::factory()->create([
        'status' => 'open',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ]);
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    // First vote
    $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint');

    // Attempt second vote programmatically to bypass HTTP and hit Service directly
    expect(fn() => 
        $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(DoubleVoteException::class);
});

it('prevents unverified users from voting', function () {
    $session = VotingSession::factory()->create(['status' => 'open']);
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    $this->actingAs($this->unverifiedVoter)
        ->post(route('voting.store', $session->slug), [
            'candidate_id' => $candidate->id,
        ])
        ->assertForbidden(); // Should be caught by middleware or policy
});

it('prevents voting in closed sessions', function () {
    $session = VotingSession::factory()->create(['status' => 'closed']);
    $candidate = Candidate::factory()->create(['voting_session_id' => $session->id]);

    expect(fn() => 
        $this->service->castVote($session->id, $candidate->id, $this->voter->id, '127.0.0.1', 'fingerprint')
    )->toThrow(\Exception::class, 'Voting session is not open.');
});
