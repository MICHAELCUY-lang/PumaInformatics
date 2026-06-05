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

class Project extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'title', 'slug', 'excerpt', 'description',
        'status', 'is_featured', 'start_date', 'completion_date',
        'github_url', 'demo_url', 'documentation_url',
        'meta_title', 'meta_description'
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'start_date' => 'date',
            'completion_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProjectCategory::class, 'category_id');
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class);
    }

    /**
     * Get the project's demo URL (shorthand for demo_url).
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->demo_url,
        );
    }

    /**
     * Get the project's content (shorthand for description).
     */
    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->description,
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('hero')
              ->width(1920)
              ->height(822) // ~21:9 cinematic
              ->format('webp')
              ->withResponsiveImages();

        $this->addMediaConversion('showcase')
              ->width(1200)
              ->height(800)
              ->format('webp');

        $this->addMediaConversion('mobile')
              ->width(640)
              ->format('webp');

        $this->addMediaConversion('og')
              ->width(1200)
              ->height(630)
              ->format('webp');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
