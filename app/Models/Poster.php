<?php

namespace App\Models;

use App\Enums\PosterStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Poster extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'published_at',
        'expires_at',
    ];

    protected $appends = [
        'is_expired',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'published_at' => 'datetime',
            'expires_at' => 'datetime',
            'status' => PosterStatus::class,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'poster_category');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PosterStatus::Published);
    }

    public function scopeScheduledToday(Builder $query): Builder
    {
        return $query->whereHas('schedules', function ($scheduleQuery) {

            $scheduleQuery
                ->where('status', 'pending')
                ->whereDate('scheduled_at', today());
        });
    }

    public function scopeByCategory(
        Builder $query,
        int $categoryId
    ): Builder {

        $categoryIds = Category::getDescendantIds($categoryId);

        $categoryIds[] = $categoryId;

        return $query->whereHas('categories', function ($categoryQuery)
            use ($categoryIds) {

            $categoryQuery->whereIn(
                'categories.id',
                $categoryIds
            );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }
}