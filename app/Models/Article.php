<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    protected $guarded = [];
    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished($q)
    {
        return $q->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    public function getReadTimeAttribute(): int
    {
        $words = count(preg_split('/\s+/u', trim(strip_tags($this->body)), -1, PREG_SPLIT_NO_EMPTY));

        return max(1, (int) ceil($words / 200));
    }
}
