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
use Illuminate\Support\Str;

class Partner extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PartnerFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'website_url', 'contact_email', 'order',
        'is_active', 'is_featured'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PartnerCategory::class, 'category_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('standard')
              ->width(400)
              ->format('webp')
              ->nonQueued(); // Fast enough for logos

        $this->addMediaConversion('retina')
              ->width(800)
              ->format('webp')
              ->nonQueued();
              
        // Assuming frontend CSS will handle grayscale filters for hover states 
        // to preserve SVG/PNG sharpness, but we provide a webp structure here.
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($partner) {
            if (empty($partner->slug)) {
                $partner->slug = Str::slug($partner->name);
            }
        });
    }
}
