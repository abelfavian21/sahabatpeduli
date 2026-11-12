@extends('layouts.app')
@section('title', 'Ajukan kampanye')
@php
    $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none transition focus:bg-white focus:ring-2 focus:ring-brand';
    $lab = 'mb-1 block text-sm font-medium';
    $err = fn ($n) => '<p class="mt-1 text-xs text-red-600">' . e($errors->first($n)) . '</p>';
    $box = 'grid gap-4 rounded-[2rem] bg-white p-6 ring-1 ring-black/5 sm:grid-cols-2 sm:p-8';
@endphp
@section('content')
<div class="mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Ajukan kampanye</h1>
    <p class="mt-3 text-neutral-600">Kamu tidak membuat kampanye sendiri. Isi pengajuan ini, tim kami akan memverifikasi, lalu membuat kampanyenya jika disetujui.</p>
    <ol class="mt-6 grid gap-3 text-sm sm:grid-cols-3">
        @foreach(['Isi pengajuan dan unggah KTP', 'Tim memverifikasi data dan berkas', 'Disetujui, kampanye tayang'] as $k => $t)
            <li class="flex items-center gap-3 rounded-2xl bg-white p-4 ring-1 ring-black/5"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-lime-deep font-semibold">{{ $k + 1 }}</span>{{ $t }}</li>
        @endforeach
    </ol>
    <p class="mt-4 text-sm">Sudah mengajukan? <a href="{{ route('requests.status') }}" class="font-medium text-brand underline">Cek status tiket</a></p>

    @if($errors->any())<div class="mt-6 rounded-2xl bg-red-50 p-4 text-sm text-red-700">Ada isian yang perlu diperbaiki. Cek pesan merah di bawah.</div>@endif

    <form method="post" enctype="multipart/form-data" action="{{ route('requests.store') }}" class="mt-8 space-y-6">
        @csrf
        <div class="hidden" aria-hidden="true"><input name="website" tabindex="-1" autocomplete="off"></div>

        <fieldset class="{{ $box }}">
            <legend class="sr-only">Data diri</legend>
            <h2 class="font-display text-xl font-extrabold sm:col-span-2">Data pengaju</h2>
            @foreach([['name', 'Nama lengkap (sesuai KTP)', 'text', ''], ['nik', 'NIK (16 digit)', 'text', 'inputmode="numeric" maxlength="16"'], ['phone', 'Nomor WhatsApp aktif', 'tel', 'placeholder="08xxxxxxxxxx"'], ['email', 'Email (opsional)', 'email', ''], ['city', 'Domisili (kota/kabupaten)', 'text', '']] as [$n, $l, $t, $x])
                <label class="{{ $n === 'city' ? 'sm:col-span-2' : '' }}"><span class="{{ $lab }}">{{ $l }}</span><input name="{{ $n }}" type="{{ $t }}" value="{{ old($n) }}" {!! $x !!} class="{{ $in }}">{!! $err($n) !!}</label>
            @endforeach
            <label><span class="{{ $lab }}">Foto KTP</span><input type="file" name="ktp" accept="image/*" class="text-sm">{!! $err('ktp') !!}</label>
            <label><span class="{{ $lab }}">Selfie memegang KTP (opsional)</span><input type="file" name="selfie" accept="image/*" class="text-sm">{!! $err('selfie') !!}</label>
            <p class="text-xs text-neutral-500 sm:col-span-2">KTP disimpan tertutup dan hanya dibuka tim verifikasi. Tidak pernah ditampilkan di situs.</p>
        </fieldset>

        <fieldset class="{{ $box }}">
            <legend class="sr-only">Tentang kampanye</legend>
            <h2 class="font-display text-xl font-extrabold sm:col-span-2">Tentang kampanye</h2>
            <label class="sm:col-span-2"><span class="{{ $lab }}">Judul kampanye</span><input name="title" value="{{ old('title') }}" class="{{ $in }}" placeholder="Contoh: Bantu biaya operasi jantung Nayla">{!! $err('title') !!}</label>
            <label><span class="{{ $lab }}">Kategori</span>
                <select name="category_id" class="{{ $in }}"><option value="">Pilih kategori</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>@endforeach</select>{!! $err('category_id') !!}</label>
            <label><span class="{{ $lab }}">Hubungan dengan penerima</span>
                <select name="relation" class="{{ $in }}"><option value="">Pilih</option>@foreach(['Diri sendiri', 'Keluarga', 'Tetangga / kerabat', 'Lembaga / komunitas'] as $o)<option @selected(old('relation') === $o)>{{ $o }}</option>@endforeach</select>{!! $err('relation') !!}</label>
            <label><span class="{{ $lab }}">Nama penerima bantuan (jika bukan kamu)</span><input name="recipient_name" value="{{ old('recipient_name') }}" class="{{ $in }}"></label>
            <label><span class="{{ $lab }}">Lokasi penerima</span><input name="location" value="{{ old('location') }}" class="{{ $in }}" placeholder="Desa, kecamatan, kabupaten">{!! $err('location') !!}</label>
            <label><span class="{{ $lab }}">Dana yang dibutuhkan (Rp)</span><input name="target" type="number" min="100000" value="{{ old('target') }}" class="{{ $in }}">{!! $err('target') !!}</label>
            <label class="sm:col-span-2"><span class="{{ $lab }}">Ceritakan kondisi dan kebutuhannya</span><textarea name="story" rows="6" class="{{ $in }}">{{ old('story') }}</textarea>{!! $err('story') !!}</label>
            <label class="sm:col-span-2"><span class="{{ $lab }}">Rencana penggunaan dana</span><textarea name="fund_usage" rows="3" class="{{ $in }}" placeholder="Contoh: biaya operasi Rp 80 juta, rawat inap Rp 20 juta">{{ old('fund_usage') }}</textarea>{!! $err('fund_usage') !!}</label>
            <label class="sm:col-span-2"><span class="{{ $lab }}">Dokumen pendukung (maks. 3: surat RT/RW, surat dokter, dll)</span><input type="file" name="evidence[]" multiple accept="image/*,application/pdf" class="text-sm">{!! $err('evidence') !!}{!! $err('evidence.0') !!}</label>
        </fieldset>

        <label class="flex gap-3 rounded-2xl bg-white p-4 text-sm ring-1 ring-black/5"><input type="checkbox" name="agree" value="1" class="mt-1" @checked(old('agree'))>
            <span>Saya menyatakan data ini benar dan setuju KTP serta berkas saya dipakai hanya untuk verifikasi oleh tim Sahabat Peduli.{!! $err('agree') !!}</span></label>

        <button class="w-full rounded-full bg-brand py-4 font-medium text-white shadow-xl shadow-brand/30 transition hover:bg-blue-600">Kirim pengajuan</button>
    </form>
</div>
@endsection
