<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    /** @use HasFactory<\Database\Factories\VoteFactory> */
    use HasFactory;

    protected $fillable = [
        'voting_session_id', 'candidate_id', 'user_id',
        'ip_hash', 'fingerprint_hash'
    ];

    // Votes are immutable. We don't use SoftDeletes.

    public function session(): BelongsTo
    {
        return $this->belongsTo(VotingSession::class, 'voting_session_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
