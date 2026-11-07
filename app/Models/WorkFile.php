<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WorkFile extends Model
{
    protected $fillable = [
        'user_id', 'original_name', 'stored_name', 'path',
        'mime_type', 'size', 'description', 'source', 'upload_date',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('files.download', $this->id);
    }

    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    public function getFileIconAttribute(): string
    {
        return match($this->extension) {
            'xlsx', 'xls' => '📊',
            'pdf'         => '📄',
            'doc', 'docx' => '📝',
            'zip', 'rar'  => '📦',
            'jpg', 'jpeg',
            'png', 'gif'  => '🖼️',
            default       => '📎',
        };
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
