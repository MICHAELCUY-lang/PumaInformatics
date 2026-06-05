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
