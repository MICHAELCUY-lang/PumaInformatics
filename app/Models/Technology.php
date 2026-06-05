<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Technology extends Model
{
    /** @use HasFactory<\Database\Factories\TechnologyFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon_svg', 'color_accent', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tech) {
            if (empty($tech->slug)) {
                $tech->slug = Str::slug($tech->name);
            }
        });
    }
}
