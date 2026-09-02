<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CabinetDepartment extends Model
{
    /** @use HasFactory<\Database\Factories\CabinetDepartmentFactory> */
    use HasFactory;

    protected $fillable = ['cabinet_id', 'name', 'slug', 'description', 'order', 'is_active'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Departments are scoped to one cabinet: each generation ran its own
     * structure, so they are never shared across terms.
     */
    public function cabinet(): BelongsTo
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CabinetMember::class, 'department_id')->orderBy('role_hierarchy_level');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($dept) {
            if (empty($dept->slug)) {
                $dept->slug = Str::slug($dept->name);
            }
        });
    }
}
