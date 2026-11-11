<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __invoke(Request $r)
    {
        $n = $r->all();

        if (! Midtrans::validSignature($n)) {
            return response()->json(['message' => 'invalid signature'], 403);
        }

        $d = Donation::where('order_id', $n['order_id'] ?? '')->first();
        if (! $d) {
            return response()->json(['message' => 'order not found'], 404);
        }

        // Jangan percaya isi notifikasi saja: konfirmasi status ke Midtrans.
        $s = Midtrans::status($d->order_id);
        if (empty($s['transaction_status'])) {
            return response()->json(['message' => 'status unavailable'], 503); // Midtrans akan mengirim ulang
        }

        $new = Midtrans::mapStatus($s);

        if ($new === 'paid' && (int) round((float) ($s['gross_amount'] ?? 0)) !== (int) $d->amount) {
            Log::warning('Midtrans: nominal tidak cocok', ['order_id' => $d->order_id, 'gross' => $s['gross_amount'] ?? null]);

            return response()->json(['message' => 'amount mismatch'], 200);
        }

        // Notifikasi bisa datang tidak berurutan: abaikan "pending" setelah "paid".
        if (! ($d->status === 'paid' && $new === 'pending')) {
            $d->markStatus($new);
        }
        if (! empty($s['payment_type'])) {
            $d->update(['payment_type' => $s['payment_type']]);
        }

        return response()->json(['ok' => true]);
    }

}
