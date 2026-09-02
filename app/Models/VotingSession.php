<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class VotingSession extends Model
{
    /** @use HasFactory<\Database\Factories\VotingSessionFactory> */
    use HasFactory, SoftDeletes, LogsActivity;

    // Canonical status vocabulary. Every layer (admin forms, public views and
    // VotingService) must speak these exact values and nothing else.
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ARCHIVED = 'archived';

    public const STATUSES = [
        self::STATUS_DRAFT,
        self::STATUS_ACTIVE,
        self::STATUS_COMPLETED,
        self::STATUS_ARCHIVED,
    ];

    // Canonical results visibility vocabulary.
    public const VISIBILITY_PRIVATE = 'private';         // admins only
    public const VISIBILITY_VOTERS_ONLY = 'voters_only'; // only users who cast a vote
    public const VISIBILITY_PUBLIC = 'public';           // everyone, once finished

    public const VISIBILITIES = [
        self::VISIBILITY_PRIVATE,
        self::VISIBILITY_VOTERS_ONLY,
        self::VISIBILITY_PUBLIC,
    ];

    protected $fillable = [
        'title', 'slug', 'description', 'status', 'results_visibility',
        'start_date', 'end_date'
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'voting_session_id')->orderBy('order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'voting_session_id');
    }

    /**
     * The single source of truth for "may a ballot be cast right now?".
     */
    public function isOpenForVoting(): bool
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        if ($this->start_date && now()->isBefore($this->start_date)) {
            return false;
        }

        if ($this->end_date && now()->isAfter($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Published, but the voting window has not started yet.
     */
    public function isUpcoming(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->start_date
            && now()->isBefore($this->start_date);
    }

    /**
     * Voting is over, either explicitly or because the window elapsed.
     */
    public function hasFinished(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_ARCHIVED], true)
            || ($this->end_date && now()->isAfter($this->end_date));
    }

    /**
     * Whether the tally may be shown to this visitor.
     */
    public function resultsVisibleTo(?User $user, bool $hasVoted = false): bool
    {
        if ($user && ($user->hasRole('Super Admin') || $user->can('manage.voting'))) {
            return true;
        }

        return match ($this->results_visibility) {
            self::VISIBILITY_PUBLIC => $this->hasFinished(),
            self::VISIBILITY_VOTERS_ONLY => $hasVoted,
            default => false,
        };
    }

    /**
     * Draft and archived sessions never appear in the public listing.
     */
    public function scopePubliclyVisible($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_COMPLETED]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (empty($session->slug)) {
                $session->slug = Str::slug($session->title);
            }
        });
    }
}
