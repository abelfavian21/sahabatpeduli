<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
