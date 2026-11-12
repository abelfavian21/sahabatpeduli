<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $guarded = [];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function getIconNameAttribute(): string
    {
        return ['bencana' => 'wave', 'pendidikan' => 'book', 'kesehatan' => 'heart', 'kemanusiaan' => 'people'][$this->slug]
            ?? ($this->icon ?: 'heart');
    }

    /** Foto kategori; jika kosong, pakai foto kampanye aktif terbaru (diisi oleh HomeController). */
    public function getCoverUrlAttribute(): ?string
    {
        $path = $this->image ?: ($this->attributes['fallback_cover'] ?? null);

        return $path ? Storage::disk('public')->url($path) : null;
    }
}
