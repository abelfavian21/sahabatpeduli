<?php

namespace App\Models;

use App\Mail\DonationReceipt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Donation extends Model
{
    protected $guarded = [];
    protected $casts = ['is_anonymous' => 'boolean', 'paid_at' => 'datetime', 'receipt_sent_at' => 'datetime'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Hamba Allah' : $this->name;
    }

    /**
     * Ubah status dan sesuaikan dana kampanye secara atomik.
     * Saat pertama kali menjadi "paid" dan donatur mengisi email, kwitansi dikirim tepat satu kali.
     */
    public function markStatus(string $new): void
    {
        $send = false;

        DB::transaction(function () use ($new, &$send) {
            $d = static::whereKey($this->id)->lockForUpdate()->first();
            if ($d->status === $new) {
                return;
            }
            $delta = ($new === 'paid' ? $d->amount : 0) - ($d->status === 'paid' ? $d->amount : 0);
            $update = ['status' => $new];
            if ($new === 'paid') {
                $update['paid_at'] = now();
                if ($d->email && ! $d->receipt_sent_at) {
                    $update['receipt_sent_at'] = now(); // "klaim" agar tidak terkirim ganda
                    $send = true;
                }
            }
            $d->update($update);
            if ($delta !== 0) {
                $d->campaign()->increment('collected', $delta);
            }
            $this->status = $new;
        });

        if ($send) {
            $this->sendReceipt();
        }
    }

    /** Kirim email terima kasih + kwitansi. Kegagalan kirim tidak boleh menggagalkan pembayaran. */
    public function sendReceipt(): bool
    {
        $d = static::with('campaign')->find($this->id);
        if (! $d || ! $d->email) {
            return false;
        }

        try {
            Mail::to($d->email)->send(new DonationReceipt($d));
            $d->forceFill(['receipt_sent_at' => now()])->save();

            return true;
        } catch (\Throwable $e) {
            report($e);
            static::whereKey($d->id)->update(['receipt_sent_at' => null]);

            return false;
        }
    }
}
