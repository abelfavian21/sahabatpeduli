<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Services\Midtrans;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function store(Request $r, Campaign $campaign)
    {
        abort_unless($campaign->status === 'active', 404);

        $data = $r->validate([
            'name' => 'required|string|max:80',
            'email' => 'nullable|email|max:120',
            'amount' => 'required|integer|min:10000',
            'message' => 'nullable|string|max:300',
        ]);
        $data['is_anonymous'] = $r->boolean('is_anonymous');

        if (! Midtrans::configured()) {
            return response()->json(['message' => 'Pembayaran belum dikonfigurasi.'], 503);
        }

        $d = $campaign->donations()->create($data + [
            'status' => 'pending',
            'order_id' => 'SP-' . now()->format('ymd') . '-' . Str::upper(Str::random(8)),
        ]);

        try {
            $d->update(['snap_token' => Midtrans::snapToken($d)]);
        } catch (\Throwable $e) {
            report($e);
            $d->delete();

            return response()->json(['message' => 'Gagal menghubungi Midtrans. Coba lagi.'], 502);
        }

        return response()->json(['token' => $d->snap_token, 'order_id' => $d->order_id]);
    }

    /** Cek status ke Midtrans lalu kembalikan progres kampanye. Aman dipanggil berulang. */
    public function sync(string $orderId)
    {
        $d = Donation::with('campaign')->where('order_id', $orderId)->firstOrFail();

        if ($d->status !== 'paid' && Midtrans::configured()) {
            $s = Midtrans::status($orderId);
            if (! empty($s['transaction_status'])) {
                $new = Midtrans::mapStatus($s);
                $amountOk = $new !== 'paid' || (int) round((float) ($s['gross_amount'] ?? 0)) === (int) $d->amount;
                if (! empty($s['payment_type'])) {
                    $d->update(['payment_type' => $s['payment_type']]);
                }
                if ($amountOk && ! ($d->status === 'paid' && $new === 'pending')) {
                    $d->markStatus($new);
                }
            }
        }

        $d->refresh();
        $c = $d->campaign->fresh();

        return response()->json([
            'status' => $d->status,
            'collected' => (int) $c->collected,
            'target' => (int) $c->target,
            'percent' => $c->percent,
            'donor' => ['name' => $d->display_name, 'amount' => (int) $d->amount, 'message' => $d->message],
        ]);
    }
}
