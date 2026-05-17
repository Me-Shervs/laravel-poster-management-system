<?php

namespace App\Models;

use App\Enums\ScheduleAction;
use App\Enums\ScheduleStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'poster_id',
        'scheduled_at',
        'action',
        'status',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'processed_at' => 'datetime',
            'action' => ScheduleAction::class,
            'status' => ScheduleStatus::class,
        ];
    }

    public function poster()
    {
        return $this->belongsTo(Poster::class);
    }
}