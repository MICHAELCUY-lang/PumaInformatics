<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Str;

class Cabinet extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'name', 'slug', 'term_year', 'is_active',
    ];

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

    public function members()
    {
        return $this->hasMany(CabinetMember::class, 'cabinet_id');
    }
}
?>
