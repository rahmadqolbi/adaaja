<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'user_id', 'category_id', 'title', 'description',
        'status', 'priority', 'deadline', 'reminder_sent',
    ];

    protected $casts = [
        'deadline' => 'date',
        'reminder_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', Carbon::today())
                     ->where('status', '!=', 'done');
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('deadline', Carbon::today())
                     ->where('status', '!=', 'done');
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'high'   => 'Tinggi',
            'medium' => 'Sedang',
            'low'    => 'Rendah',
            default  => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'Belum Dikerjakan',
            'in_progress' => 'Sedang Dikerjakan',
            'done'        => 'Selesai',
            default       => '-',
        };
    }

    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'high'   => 'badge-danger',
            'medium' => 'badge-warning',
            'low'    => 'badge-success',
            default  => 'badge-secondary',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending'     => 'badge-secondary',
            'in_progress' => 'badge-primary',
            'done'        => 'badge-success',
            default       => 'badge-secondary',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->deadline
            && $this->deadline->isPast()
            && $this->status !== 'done';
    }
}
