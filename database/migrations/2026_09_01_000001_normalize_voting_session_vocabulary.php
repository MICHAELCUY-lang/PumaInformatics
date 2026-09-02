<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The codebase historically carried three different vocabularies for
 * voting_sessions.status and two for results_visibility, which made it
 * impossible to ever cast a vote. App\Models\VotingSession is now the single
 * source of truth; this migration folds any legacy rows onto that vocabulary.
 */
return new class extends Migration
{
    private const STATUS_MAP = [
        'open' => 'active',
        'scheduled' => 'draft',
        'upcoming' => 'draft',
        'closed' => 'completed',
    ];

    private const VISIBILITY_MAP = [
        'hidden' => 'private',
        'admin_only' => 'private',
        'live' => 'public',
    ];

    public function up(): void
    {
        foreach (self::STATUS_MAP as $legacy => $canonical) {
            DB::table('voting_sessions')->where('status', $legacy)->update(['status' => $canonical]);
        }

        foreach (self::VISIBILITY_MAP as $legacy => $canonical) {
            DB::table('voting_sessions')->where('results_visibility', $legacy)->update(['results_visibility' => $canonical]);
        }

        // Anything still outside the canonical set is parked as a draft rather
        // than left in a state no layer of the app understands.
        DB::table('voting_sessions')
            ->whereNotIn('status', ['draft', 'active', 'completed', 'archived'])
            ->update(['status' => 'draft']);

        DB::table('voting_sessions')
            ->whereNotIn('results_visibility', ['private', 'voters_only', 'public'])
            ->update(['results_visibility' => 'private']);
    }

    public function down(): void
    {
        // Intentionally irreversible: the legacy values were ambiguous and
        // mapping back would guess at which of them a row originally used.
    }
};
