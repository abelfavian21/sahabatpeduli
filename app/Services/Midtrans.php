<?php

namespace App\Services;

use App\Models\Donation;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Midtrans
{
    public static function configured(): bool
    {
        return filled(config('midtrans.server_key')) && filled(config('midtrans.client_key'));
    }

    private static function http(): PendingRequest
    {
        return Http::withBasicAuth((string) config('midtrans.server_key'), '')->acceptJson()->asJson()->timeout(20);
    }

    /** Buat transaksi Snap, kembalikan snap token. */
    public static function snapToken(Donation $d): string
    {
        $c = $d->campaign;
        $payload = [
            'transaction_details' => ['order_id' => $d->order_id, 'gross_amount' => (int) $d->amount],
            'item_details' => [[
                'id' => 'CAMP-' . $c->id, 'price' => (int) $d->amount, 'quantity' => 1,
                'name' => Str::limit('Donasi: ' . $c->title, 50, ''),
            ]],
            'customer_details' => array_filter(['first_name' => Str::limit($d->name, 40, ''), 'email' => $d->email]),
            'callbacks' => ['finish' => route('campaigns.show', $c)],
            'expiry' => ['unit' => 'hours', 'duration' => 24],
        ];

        $res = self::http()->post(config('midtrans.snap_api') . '/snap/v1/transactions', $payload)->throw();

        return $res->json('token');
    }

    /** Ambil status transaksi langsung dari Midtrans (dipakai untuk memverifikasi notifikasi). */
    public static function status(string $orderId): array
    {
        return self::http()->get(config('midtrans.core_api') . '/v2/' . rawurlencode($orderId) . '/status')->json() ?? [];
    }

    /** SHA512(order_id + status_code + gross_amount + server_key) */
    public static function validSignature(array $n): bool
    {
        $expected = hash('sha512', ($n['order_id'] ?? '') . ($n['status_code'] ?? '') . ($n['gross_amount'] ?? '') . config('midtrans.server_key'));

        return hash_equals($expected, (string) ($n['signature_key'] ?? ''));
    }

    /** Petakan status Midtrans ke status donasi: paid | failed | expired | pending */
    public static function mapStatus(array $s): string
    {
        $t = $s['transaction_status'] ?? '';
        $fraud = $s['fraud_status'] ?? 'accept';
        $ok = ($s['status_code'] ?? '') === '200';

        return match (true) {
            $ok && ($t === 'settlement' || ($t === 'capture' && $fraud === 'accept')) => 'paid',
            in_array($t, ['deny', 'cancel', 'failure', 'refund', 'partial_refund', 'chargeback'], true) => 'failed',
            $t === 'expire' => 'expired',
            default => 'pending',
        };
    }
}
