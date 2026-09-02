<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Cabinet extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'term_year', 'is_active', 'tagline', 'generation',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'generation' => 'integer',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function (self $cabinet) {
            if (empty($cabinet->slug)) {
                $cabinet->slug = Str::slug($cabinet->name);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function members(): HasMany
    {
        return $this->hasMany(CabinetMember::class, 'cabinet_id');
    }

    /**
     * Departments belong to a single cabinet: each generation ran its own
     * structure, so they are not shared across terms.
     */
    public function departments(): HasMany
    {
        return $this->hasMany(CabinetDepartment::class, 'cabinet_id')->orderBy('order');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'cabinet_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        // ->nonOptimized() is mandatory on this host: see DOCUMENTATION.md.
        $this->addMediaConversion('emblem')->nonOptimized()
             ->width(320)
             ->height(320)
             ->format('webp')
             ->nonQueued();
    }

    /**
     * Logo URL, falling back to the site mark so the cabinet switcher never
     * renders a broken image for a generation whose logo was never uploaded.
     */
    public function logoUrl(): string
    {
        $media = $this->getFirstMedia('logo');

        if (! $media) {
            return asset('logo.png');
        }

        return $media->hasGeneratedConversion('emblem')
            ? $media->getUrl('emblem')
            : $media->getUrl();
    }
}
