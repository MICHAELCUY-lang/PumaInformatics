<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AspirationCategory extends Model
{
    /** @use HasFactory<\Database\Factories\AspirationCategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function aspirations(): HasMany
    {
        return $this->hasMany(Aspiration::class, 'category_id')->orderBy('created_at', 'desc');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
