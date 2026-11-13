<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    protected $fillable = ['user_id', 'parent_id', 'name', 'description', 'is_shared'];

    protected $casts = ['is_shared' => 'boolean'];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(WorkFile::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopeRootFolders($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('is_shared', true);
        });
    }

    // ── Accessors ─────────────────────────────────────────────────────────────

    public function getFullPathAttribute(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;
        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }
        return $path->implode(' / ');
    }

    public function getTotalFilesAttribute(): int
    {
        return $this->files()->count();
    }
}
