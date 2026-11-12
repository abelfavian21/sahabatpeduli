<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignRequestController extends Controller
{
    public function index(Request $r)
    {
        $requests = CampaignRequest::with('category')
            ->when($r->status, fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15)->withQueryString();

        return view('admin.requests.index', compact('requests'));
    }

    public function show(CampaignRequest $campaignRequest)
    {
        return view('admin.requests.show', ['req' => $campaignRequest->load('category', 'campaign')]);
    }

    public function update(Request $r, CampaignRequest $campaignRequest)
    {
        $d = $r->validate([
            'status' => 'required|in:' . implode(',', array_keys(CampaignRequest::STATUS)),
            'admin_note' => 'nullable|string|max:1000',
        ]);
        $campaignRequest->update($d);

        return back()->with('ok', 'Pengajuan diperbarui.');
    }

    /** Buat draft kampanye dari pengajuan. Admin lengkapi foto lalu terbitkan sendiri. */
    public function convert(CampaignRequest $campaignRequest)
    {
        if ($campaignRequest->campaign_id) {
            return redirect()->route('admin.campaigns.edit', $campaignRequest->campaign_id);
        }

        $c = Campaign::create([
            'category_id' => $campaignRequest->category_id,
            'title' => $campaignRequest->title,
            'slug' => Str::slug($campaignRequest->title) . '-' . Str::lower(Str::random(4)),
            'organizer' => $campaignRequest->name,
            'excerpt' => Str::limit(trim(preg_replace('/\s+/', ' ', $campaignRequest->story)), 160),
            'story' => $campaignRequest->story . "\n\nRencana penggunaan dana:\n" . $campaignRequest->fund_usage,
            'target' => $campaignRequest->target,
            'deadline' => now()->addDays(30),
            'status' => 'draft',
        ]);
        $campaignRequest->update(['status' => 'approved', 'campaign_id' => $c->id]);

        return redirect()->route('admin.campaigns.edit', $c)->with('ok', 'Draft kampanye dibuat dari pengajuan. Tambahkan foto, cek isinya, lalu ubah status ke Aktif.');
    }

    /** Berkas identitas hanya lewat sini (admin), tidak pernah lewat URL publik. */
    public function file(CampaignRequest $campaignRequest, string $type, int $i = 0)
    {
        $path = match ($type) {
            'ktp' => $campaignRequest->ktp_photo,
            'selfie' => $campaignRequest->selfie_photo,
            'evidence' => $campaignRequest->evidence[$i] ?? null,
            default => null,
        };
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return Storage::disk('local')->response($path, null, ['Cache-Control' => 'private, no-store']);
    }

    public function destroy(CampaignRequest $campaignRequest)
    {
        $files = array_filter(array_merge([$campaignRequest->ktp_photo, $campaignRequest->selfie_photo], $campaignRequest->evidence ?? []));
        Storage::disk('local')->delete($files);
        $campaignRequest->delete();

        return redirect()->route('admin.requests.index')->with('ok', 'Pengajuan dan berkasnya dihapus.');
    }
}
