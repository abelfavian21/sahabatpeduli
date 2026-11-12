<?php

namespace App\Http\Controllers;

use App\Models\CampaignRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignRequestController extends Controller
{
    public function create()
    {
        return view('requests.create', ['categories' => Category::all()]);
    }

    public function store(Request $r)
    {
        if (filled($r->website)) { // honeypot: bot mengisi kolom tersembunyi
            return redirect()->route('home');
        }
        $r->merge(['phone' => preg_replace('/[\s\-]/', '', (string) $r->phone)]);

        $d = $r->validate([
            'name' => 'required|string|max:100',
            'nik' => 'required|digits:16',
            'phone' => ['required', 'regex:/^(\+62|62|0)8\d{7,12}$/'],
            'email' => 'nullable|email|max:120',
            'city' => 'required|string|max:100',
            'ktp' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'selfie' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'category_id' => 'required|exists:categories,id',
            'relation' => 'required|in:Diri sendiri,Keluarga,Tetangga / kerabat,Lembaga / komunitas',
            'recipient_name' => 'nullable|string|max:100',
            'location' => 'required|string|max:150',
            'title' => 'required|string|max:160',
            'story' => 'required|string|min:50|max:5000',
            'fund_usage' => 'required|string|min:20|max:3000',
            'target' => 'required|integer|min:100000|max:10000000000',
            'evidence' => 'nullable|array|max:3',
            'evidence.*' => 'file|mimes:jpg,jpeg,png,pdf|max:4096',
            'agree' => 'accepted',
        ], $this->messages(), $this->attributes());

        // Berkas identitas disimpan di disk privat (bukan public), hanya bisa dibuka admin.
        $evidence = collect($r->file('evidence', []))->map(fn ($f) => $f->store('requests/evidence', 'local'))->all();

        do {
            $code = 'REQ-' . strtoupper(Str::random(6));
        } while (CampaignRequest::where('ticket_code', $code)->exists());

        CampaignRequest::create([
            'ticket_code' => $code,
            'name' => $d['name'], 'nik' => $d['nik'], 'phone' => $d['phone'], 'email' => $d['email'] ?? null, 'city' => $d['city'],
            'ktp_photo' => $r->file('ktp')->store('requests/ktp', 'local'),
            'selfie_photo' => $r->hasFile('selfie') ? $r->file('selfie')->store('requests/selfie', 'local') : null,
            'evidence' => $evidence ?: null,
            'category_id' => $d['category_id'], 'relation' => $d['relation'], 'recipient_name' => $d['recipient_name'] ?? null,
            'location' => $d['location'], 'title' => $d['title'], 'story' => $d['story'], 'fund_usage' => $d['fund_usage'],
            'target' => $d['target'],
        ]);

        return redirect()->route('requests.status')->with('ticket', $code);
    }

    public function statusForm()
    {
        return view('requests.status');
    }

    public function statusCheck(Request $r)
    {
        $in = $r->validate(['code' => 'required|string|max:20', 'phone' => 'required|string|max:20']);
        $norm = function ($p) {
            $x = preg_replace('/\D/', '', $p);

            return str_starts_with($x, '62') ? '0' . substr($x, 2) : $x;
        };

        $req = CampaignRequest::with('campaign')->where('ticket_code', strtoupper(trim($in['code'])))->first();

        if (! $req || $norm($req->phone) !== $norm($in['phone'])) {
            return back()->withErrors(['code' => 'Kode tiket atau nomor WhatsApp tidak cocok.'])->withInput();
        }

        return view('requests.status', ['found' => $req]);
    }

    private function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.', 'digits' => ':attribute harus :digits digit.', 'min' => ':attribute terlalu pendek atau kecil.',
            'max' => ':attribute terlalu panjang atau besar.', 'mimes' => 'Format :attribute tidak didukung.', 'image' => ':attribute harus berupa gambar.',
            'accepted' => 'Kamu perlu menyetujui pernyataan ini.', 'email' => 'Format email tidak valid.', 'regex' => 'Format :attribute tidak valid.',
            'exists' => ':attribute tidak valid.', 'in' => ':attribute tidak valid.', 'integer' => ':attribute harus berupa angka.',
        ];
    }

    private function attributes(): array
    {
        return [
            'name' => 'Nama', 'nik' => 'NIK', 'phone' => 'Nomor WhatsApp', 'city' => 'Domisili', 'ktp' => 'Foto KTP', 'selfie' => 'Foto selfie',
            'category_id' => 'Kategori', 'relation' => 'Hubungan dengan penerima', 'location' => 'Lokasi', 'title' => 'Judul', 'story' => 'Cerita',
            'fund_usage' => 'Rencana penggunaan dana', 'target' => 'Target dana', 'evidence' => 'Dokumen pendukung', 'evidence.*' => 'Dokumen pendukung',
        ];
    }
}
