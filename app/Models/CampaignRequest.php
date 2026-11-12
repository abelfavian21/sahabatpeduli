<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRequest extends Model
{
    protected $guarded = [];
    protected $casts = ['nik' => 'encrypted', 'evidence' => 'array'];

    public const STATUS = [
        'pending' => 'Menunggu ditinjau',
        'reviewing' => 'Sedang ditinjau',
        'need_info' => 'Perlu informasi tambahan',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ];

    public const BADGE = [
        'pending' => 'bg-amber-100',
        'reviewing' => 'bg-sky-100',
        'need_info' => 'bg-orange-100',
        'approved' => 'bg-lime-sp',
        'rejected' => 'bg-red-100',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getMaskedNikAttribute(): string
    {
        $n = (string) $this->nik;

        return strlen($n) === 16 ? substr($n, 0, 4) . str_repeat('•', 8) . substr($n, -4) : '';
    }
}
