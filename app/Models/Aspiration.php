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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Aspiration extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\AspirationFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'category_id', 'subject', 'payload',
        'status', 'visibility', 'is_anonymous',
        'admin_notes', 'ip_hash'
    ];

    protected function casts(): array
    {
        return [
            'is_anonymous' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        // We log status changes specifically for auditing
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AspirationCategory::class, 'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
             ->acceptsMimeTypes(['application/pdf', 'image/jpeg', 'image/png', 'image/webp'])
             ->useDisk('private'); // Storing securely outside public visibility initially
    }
}
