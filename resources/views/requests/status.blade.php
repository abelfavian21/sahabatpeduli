@extends('layouts.app')
@section('title', 'Status pengajuan')
@php $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none transition focus:bg-white focus:ring-2 focus:ring-brand'; @endphp
@section('content')
<div class="mx-auto max-w-xl py-10">
    <h1 class="font-display text-4xl font-extrabold">Status pengajuan</h1>

    @if(session('ticket'))
        <div class="mt-6 rounded-[2rem] bg-lime-sp p-6">
            <p class="font-semibold">Pengajuan terkirim. Simpan kode tiketmu:</p>
            <p class="mt-2 font-display text-3xl font-extrabold tracking-wider">{{ session('ticket') }}</p>
            <p class="mt-2 text-sm">Pakai kode ini dan nomor WhatsApp-mu untuk mengecek status. Tim kami akan menghubungi lewat WhatsApp jika butuh informasi tambahan.</p>
        </div>
    @endif

    @isset($found)
        <div class="mt-6 rounded-[2rem] bg-white p-6 ring-1 ring-black/5">
            <p class="text-sm text-neutral-500">{{ $found->ticket_code }}</p>
            <h2 class="mt-1 font-display text-2xl font-extrabold">{{ $found->title }}</h2>
            <span class="mt-3 inline-block rounded-full px-4 py-1.5 text-sm font-medium {{ \App\Models\CampaignRequest::BADGE[$found->status] ?? 'bg-neutral-100' }}">{{ $found->status_label }}</span>
            @if($found->admin_note)<p class="mt-4 rounded-2xl bg-neutral-100 p-4 text-sm"><b>Catatan tim:</b> {{ $found->admin_note }}</p>@endif
            @if($found->campaign && $found->campaign->status === 'active')<a href="{{ route('campaigns.show', $found->campaign) }}" class="mt-4 inline-block rounded-full bg-brand px-6 py-3 text-sm font-medium text-white">Lihat kampanye</a>@endif
        </div>
    @endisset

    <form method="post" action="{{ route('requests.check') }}" class="mt-6 space-y-4 rounded-[2rem] bg-white p-6 ring-1 ring-black/5">
        @csrf
        <h2 class="font-display text-xl font-extrabold">Cek status tiket</h2>
        <input name="code" value="{{ old('code') }}" placeholder="Kode tiket (REQ-XXXXXX)" class="{{ $in }}">
        <input name="phone" value="{{ old('phone') }}" placeholder="Nomor WhatsApp yang dipakai mengajukan" class="{{ $in }}">
        <p class="text-sm text-red-600">{{ $errors->first('code') ?: $errors->first('phone') }}</p>
        <button class="w-full rounded-full bg-ink py-3 text-sm font-medium text-white transition hover:bg-brand">Cek status</button>
    </form>
    <a href="{{ route('requests.create') }}" class="mt-6 inline-block text-sm text-brand underline">Buat pengajuan baru</a>
</div>
@endsection
