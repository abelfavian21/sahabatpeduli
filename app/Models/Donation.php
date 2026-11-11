<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
    protected $guarded = [];
    protected $casts = ['is_anonymous' => 'boolean'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Hamba Allah' : $this->name;
    }

    /** Ubah status dan sesuaikan dana kampanye secara atomik (paid menambah, keluar dari paid mengurangi). */
    public function markStatus(string $new): void
    {
        DB::transaction(function () use ($new) {
            $d = static::whereKey($this->id)->lockForUpdate()->first();
            if ($d->status === $new) {
                return;
            }
            $delta = ($new === 'paid' ? $d->amount : 0) - ($d->status === 'paid' ? $d->amount : 0);
            $d->update(['status' => $new]);
            if ($delta !== 0) {
                $d->campaign()->increment('collected', $delta);
            }
            $this->status = $new;
        });
    }
}
