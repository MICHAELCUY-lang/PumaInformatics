<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Navigation extends Model
{
    /** @use HasFactory<\Database\Factories\NavigationFactory> */
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'url',
        'is_external',
        'is_active',
        'order',
        'visibility_roles',
    ];

    protected function casts(): array
    {
        return [
            'is_external' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'integer',
            'visibility_roles' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Navigation::class, 'parent_id')->orderBy('order');
    }
}
