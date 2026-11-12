@extends('admin.layout')
@section('title', $req->ticket_code)
@php $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand'; $wa = preg_replace('/^0/', '62', preg_replace('/\D/', '', $req->phone)); @endphp
@section('content')
<a href="{{ route('admin.requests.index') }}" class="text-sm text-neutral-500">&larr; Semua pengajuan</a>
<div class="mb-6 mt-2 flex flex-wrap items-center gap-3">
    <h1 class="text-2xl font-bold">{{ $req->ticket_code }}</h1>
    <span class="rounded-full px-3 py-1 text-xs {{ \App\Models\CampaignRequest::BADGE[$req->status] ?? 'bg-neutral-100' }}">{{ $req->status_label }}</span>
</div>

<div class="grid gap-6 xl:grid-cols-[1fr_24rem]">
    <div class="space-y-6">
        <section class="rounded-2xl bg-white p-6">
            <h2 class="mb-3 font-semibold">Pengaju</h2>
            <dl class="grid gap-3 text-sm sm:grid-cols-2">
                <div><dt class="text-neutral-500">Nama</dt><dd class="font-medium">{{ $req->name }}</dd></div>
                <div><dt class="text-neutral-500">NIK</dt><dd class="font-medium"><span id="nik" data-full="{{ $req->nik }}" data-mask="{{ $req->masked_nik }}">{{ $req->masked_nik }}</span>
                    <button type="button" class="ml-2 text-brand" onclick="const e=document.getElementById('nik');e.textContent=e.textContent===e.dataset.mask?e.dataset.full:e.dataset.mask">tampilkan/sembunyikan</button></dd></div>
                <div><dt class="text-neutral-500">WhatsApp</dt><dd class="font-medium"><a href="https://wa.me/{{ $wa }}" target="_blank" class="text-brand">{{ $req->phone }}</a></dd></div>
                <div><dt class="text-neutral-500">Email</dt><dd class="font-medium">{{ $req->email ?: '-' }}</dd></div>
                <div><dt class="text-neutral-500">Domisili</dt><dd class="font-medium">{{ $req->city }}</dd></div>
                <div><dt class="text-neutral-500">Hubungan dengan penerima</dt><dd class="font-medium">{{ $req->relation }}{{ $req->recipient_name ? ' · '.$req->recipient_name : '' }}</dd></div>
            </dl>
        </section>

        <section class="rounded-2xl bg-white p-6">
            <h2 class="mb-3 font-semibold">Kampanye</h2>
            <p class="text-lg font-semibold">{{ $req->title }}</p>
            <p class="mt-1 text-sm text-neutral-500">{{ $req->category?->name }} · {{ $req->location }} · target Rp {{ number_format($req->target, 0, ',', '.') }}</p>
            <h3 class="mt-4 text-sm font-medium">Cerita</h3><p class="mt-1 whitespace-pre-line text-sm text-neutral-700">{{ $req->story }}</p>
            <h3 class="mt-4 text-sm font-medium">Rencana penggunaan dana</h3><p class="mt-1 whitespace-pre-line text-sm text-neutral-700">{{ $req->fund_usage }}</p>
        </section>

        <section class="rounded-2xl bg-white p-6">
            <h2 class="mb-3 font-semibold">Berkas (hanya admin yang bisa membuka)</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div><p class="mb-1 text-sm text-neutral-500">KTP</p><a href="{{ route('admin.requests.file', [$req, 'ktp']) }}" target="_blank"><img src="{{ route('admin.requests.file', [$req, 'ktp']) }}" alt="KTP" class="rounded-xl ring-1 ring-black/10"></a></div>
                @if($req->selfie_photo)<div><p class="mb-1 text-sm text-neutral-500">Selfie dengan KTP</p><a href="{{ route('admin.requests.file', [$req, 'selfie']) }}" target="_blank"><img src="{{ route('admin.requests.file', [$req, 'selfie']) }}" alt="Selfie" class="rounded-xl ring-1 ring-black/10"></a></div>@endif
            </div>
            @if($req->evidence)<p class="mt-4 text-sm text-neutral-500">Dokumen pendukung</p>
                <div class="mt-1 flex flex-wrap gap-2">@foreach($req->evidence as $i => $f)<a href="{{ route('admin.requests.file', [$req, 'evidence', $i]) }}" target="_blank" class="rounded-full bg-neutral-100 px-4 py-2 text-sm hover:bg-neutral-200">Dokumen {{ $i + 1 }}</a>@endforeach</div>@endif
        </section>
    </div>

    <aside class="space-y-4 xl:sticky xl:top-6 xl:h-fit">
        <form method="post" action="{{ route('admin.requests.update', $req) }}" class="space-y-3 rounded-2xl bg-white p-5">
            @csrf @method('PATCH')
            <h2 class="font-semibold">Tindak lanjut</h2>
            <select name="status" class="{{ $in }}">@foreach(\App\Models\CampaignRequest::STATUS as $v => $l)<option value="{{ $v }}" @selected($req->status === $v)>{{ $l }}</option>@endforeach</select>
            <textarea name="admin_note" rows="4" placeholder="Catatan untuk pengaju (tampil di halaman cek status)" class="{{ $in }}">{{ old('admin_note', $req->admin_note) }}</textarea>
            <button class="w-full rounded-full bg-brand py-2.5 text-sm font-medium text-white">Simpan</button>
        </form>

        <div class="space-y-3 rounded-2xl bg-white p-5">
            @if($req->campaign)
                <p class="text-sm">Sudah dibuat jadi kampanye.</p>
                <a href="{{ route('admin.campaigns.edit', $req->campaign) }}" class="block rounded-full bg-neutral-900 py-2.5 text-center text-sm font-medium text-white">Buka kampanye</a>
            @else
                <form method="post" action="{{ route('admin.requests.convert', $req) }}" onsubmit="return confirm('Buat draft kampanye dari pengajuan ini?')">@csrf
                    <button class="w-full rounded-full bg-neutral-900 py-2.5 text-sm font-medium text-white">Setujui dan buat draft kampanye</button></form>
                <p class="text-xs text-neutral-500">Dibuat sebagai draft. Lengkapi foto, cek isinya, lalu aktifkan.</p>
            @endif
        </div>

        <form method="post" action="{{ route('admin.requests.destroy', $req) }}" onsubmit="return confirm('Hapus pengajuan beserta KTP dan semua berkasnya? Tidak bisa dibatalkan.')" class="rounded-2xl bg-white p-5">
            @csrf @method('DELETE')
            <button class="w-full rounded-full py-2.5 text-sm text-red-600 ring-1 ring-red-200 hover:bg-red-50">Hapus pengajuan dan berkas</button>
        </form>
    </aside>
</div>
@endsection
