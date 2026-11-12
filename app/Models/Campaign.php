<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];
    protected $casts = ['deadline' => 'date', 'is_urgent' => 'boolean', 'is_verified' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function getPercentAttribute(): int
    {
        return $this->target > 0 ? min(100, (int) round($this->collected / $this->target * 100)) : 0;
    }

    public function getDaysLeftAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->deadline, false));
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->image) : null;
    }

    public function getVideoUrlAttribute(): ?string
    {
        return $this->video ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->video) : null;
    }
}
