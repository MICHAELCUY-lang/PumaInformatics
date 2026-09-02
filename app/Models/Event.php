<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class Event extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'title', 'slug', 'description', 'excerpt',
        'status', 'is_featured', 'start_date', 'end_date', 'timezone',
        'location_name', 'location_address', 'location_coordinates',
        'external_registration_url', 'internal_rsvp_enabled',
        'meta_title', 'meta_description'
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'internal_rsvp_enabled' => 'boolean',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'location_coordinates' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(EventTag::class, 'event_tag');
    }

    /**
     * Get the event's location name (shorthand for location_name).
     */
    protected function location(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->location_name,
        );
    }

    /**
     * Get the event's content (shorthand for description).
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->description,
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->nonOptimized()
              ->width(400)
              ->height(300)
              ->sharpen(10)
              ->format('webp');

        $this->addMediaConversion('card')->nonOptimized()
              ->width(800)
              ->height(600)
              ->format('webp');

        $this->addMediaConversion('hero')->nonOptimized()
              ->width(1920)
              ->height(822) // ~21:9 cinematic ratio
              ->format('webp');
              
        $this->addMediaConversion('mobile-optimized')->nonOptimized()
              ->width(640)
              ->format('webp');
    }

    public static function generateUniqueSlug($slugBase, $ignoreId = 0)
    {
        $slug = Str::slug($slugBase);
        $originalSlug = $slug;
        $count = 1;

        while (static::withTrashed()->where('slug', $slug)->where('id', '!=', $ignoreId)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $slugBase = empty($event->slug) ? $event->title : $event->slug;
            $event->slug = static::generateUniqueSlug($slugBase);
        });

        static::updating(function ($event) {
            if ($event->isDirty('title')) {
                // If title changed, we generate a new slug. We don't respect manual slugs here because there's no UI for it.
                $event->slug = static::generateUniqueSlug($event->title, $event->id);
            } elseif ($event->isDirty('slug')) {
                $event->slug = static::generateUniqueSlug($event->slug, $event->id);
            }
        });
    }
}
