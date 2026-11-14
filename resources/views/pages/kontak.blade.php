@extends('layouts.app')
@section('title', 'Kontak')
@php $o = config('sahabat'); @endphp
@section('content')
<article class="legal mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Kontak</h1>
    <p class="!text-sm !text-neutral-500">Terakhir diperbarui: {{ $o['updated'] }}</p>

    @php $wa = preg_replace('/\D/', '', $o['whatsapp']); $wa = str_starts_with($wa, '0') ? '62' . substr($wa, 1) : $wa; @endphp
    <p>Ada pertanyaan, masukan, atau ingin melaporkan sesuatu? Hubungi kami lewat jalur berikut.</p>

    <dl>
        <div><dt class="text-sm text-neutral-500">Email</dt><dd class="font-medium"><a href="mailto:{{ $o['email'] }}">{{ $o['email'] }}</a></dd></div>
        <div><dt class="text-sm text-neutral-500">WhatsApp</dt><dd class="font-medium">@if(strlen($wa) >= 9)<a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener">{{ $o['whatsapp'] }}</a>@else{{ $o['whatsapp'] }}@endif</dd></div>
        <div><dt class="text-sm text-neutral-500">Alamat</dt><dd class="font-medium">{{ $o['org_address'] }}</dd></div>
    </dl>

    <h2>Laporkan kampanye mencurigakan</h2>
    <p>Kirim tautan kampanye dan alasan laporanmu ke email di atas dengan subjek “Laporan kampanye”. Sertakan bukti jika ada. Kami meninjau setiap laporan.</p>

    <h2>Permintaan data pribadi</h2>
    <p>Untuk akses, perbaikan, atau penghapusan data, tulis ke <a href="mailto:{{ $o['privacy_email'] }}">{{ $o['privacy_email'] }}</a>.</p>

    <h2>Pengajuan kampanye</h2>
    <p>Ingin mengajukan kampanye? Pakai <a href="{{ route('requests.create') }}">formulir pengajuan</a> dan pantau lewat <a href="{{ route('requests.status') }}">cek status tiket</a>.</p>
</article>
@endsection
