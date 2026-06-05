<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class EventTag extends Model
{
    /** @use HasFactory<\Database\Factories\EventTagFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }
}
