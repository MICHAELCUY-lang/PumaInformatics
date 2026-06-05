<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Candidate extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CandidateFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'voting_session_id', 'name', 'slug', 'vision', 'mission',
        'biography', 'achievements', 'social_links', 'order', 'is_featured'
    ];

    protected function casts(): array
    {
        return [
            'social_links' => 'json',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(VotingSession::class, 'voting_session_id');
    }

    public function votes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portrait')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
