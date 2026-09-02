<?php

namespace App\Services;

use App\Models\VotingSession;
use App\Models\Vote;
use App\Models\Candidate;
use App\Exceptions\DoubleVoteException;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class VotingService
{
    /**
     * Casts a vote securely using pessimistic locking and DB constraints.
     * 
     * @throws DoubleVoteException
     * @throws \Exception
     */
    public function castVote(int $sessionId, int $candidateId, int $userId, string $ip, string $fingerprint)
    {
        return DB::transaction(function () use ($sessionId, $candidateId, $userId, $ip, $fingerprint) {
            // 1. Lock the session row to prevent race condition state changes during vote processing
            $session = VotingSession::where('id', $sessionId)
                                    ->lockForUpdate()
                                    ->firstOrFail();

            // 2. Validate Session State
            if ($session->status !== VotingSession::STATUS_ACTIVE) {
                throw new \Exception('Voting session is not open.');
            }

            if (! $session->isOpenForVoting()) {
                throw new \Exception('Voting session is currently outside the valid time window.');
            }

            // 2b. The ballot must belong to THIS session. Without this check a voter
            // could post a candidate_id from another session into the ledger.
            $candidateBelongsToSession = Candidate::where('id', $candidateId)
                                                  ->where('voting_session_id', $sessionId)
                                                  ->exists();

            if (! $candidateBelongsToSession) {
                throw new \Exception('The selected candidate is not part of this voting session.');
            }

            // 3. Application-Level Check
            $existingVote = Vote::where('voting_session_id', $sessionId)
                                ->where('user_id', $userId)
                                ->lockForUpdate()
                                ->exists();

            if ($existingVote) {
                throw new DoubleVoteException();
            }

            // 4. Secure Hash Generation
            $ipHash = hash('sha256', $ip . config('app.key'));
            $fingerprintHash = hash('sha256', $fingerprint . config('app.key'));

            try {
                // 5. DB Insertion (final barrier: unique DB constraint prevents actual races that bypass app locks)
                $vote = Vote::create([
                    'voting_session_id' => $sessionId,
                    'candidate_id' => $candidateId,
                    'user_id' => $userId,
                    'ip_hash' => $ipHash,
                    'fingerprint_hash' => $fingerprintHash,
                ]);

                // We don't need to manually log Activity here because we aren't using Spatie for Votes 
                // to save overhead, the Votes table IS the immutable ledger.
                // But we could dispatch an event if needed.

                return $vote;

            } catch (QueryException $e) {
                // Error code 23000 is Integrity constraint violation (Unique constraint failed)
                if ($e->getCode() == '23000') {
                    throw new DoubleVoteException();
                }

                throw $e;
            }
        });
    }
}
