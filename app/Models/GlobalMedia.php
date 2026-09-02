<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GlobalMedia extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'uuid', 'user_id', 'status', 'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('editor_uploads')
             ->singleFile() // Each GlobalMedia row represents exactly one uploaded file
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Tiptap Editor / Editorial responsive size (WebP forced)
        $this->addMediaConversion('editorial')->nonOptimized()
             ->width(1024)
             ->format('webp')
             ->nonQueued(); // For immediate return to Tiptap

        $this->addMediaConversion('thumbnail')->nonOptimized()
             ->width(300)
             ->height(300)
             ->format('webp')
             ->nonQueued();
    }
}
