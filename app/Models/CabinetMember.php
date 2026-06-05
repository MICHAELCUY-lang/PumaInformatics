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

class CabinetMember extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\CabinetMemberFactory> */
    use HasFactory, SoftDeletes, LogsActivity, InteractsWithMedia;

    protected $fillable = [
        'department_id', 'cabinet_id', 'name', 'slug', 'role_title',
        'role_hierarchy_level', 'term_year', 'is_active',
        'biography', 'achievements', 'social_links'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'role_hierarchy_level' => 'integer',
            'achievements' => 'array',
            'social_links' => 'array',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty()->dontSubmitEmptyLogs();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(CabinetDepartment::class, 'department_id');
    }

    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
              ->width(200)
              ->height(200)
              ->sharpen(10)
              ->format('webp');

        $this->addMediaConversion('portrait')
              ->width(600)
              ->height(800)
              ->format('webp')
              ->withResponsiveImages();
              
        $this->addMediaConversion('featured')
              ->width(1200)
              ->height(1200)
              ->format('webp');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {
            if (empty($member->slug)) {
                $member->slug = Str::slug($member->name . '-' . $member->term_year);
            }
        });
    }
}
