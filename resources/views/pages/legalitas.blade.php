@extends('layouts.app')
@section('title', 'Legalitas dan izin')
@php $o = config('sahabat'); @endphp
@section('content')
<article class="legal mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Legalitas &amp; Izin</h1>
    <p class="!text-sm !text-neutral-500">Terakhir diperbarui: {{ $o['updated'] }}</p>

    <p>Halaman ini menyajikan identitas penyelenggara dan izin yang kami miliki agar donatur dapat memeriksanya.</p>

    <h2>Penyelenggara</h2>
    <dl>
        <div><dt class="text-sm text-neutral-500">Nama lembaga</dt><dd class="font-medium">{{ $o['org_name'] }}</dd></div>
        <div><dt class="text-sm text-neutral-500">Pengesahan badan hukum</dt><dd class="font-medium">{{ $o['org_legal'] }}</dd></div>
        <div><dt class="text-sm text-neutral-500">Alamat</dt><dd class="font-medium">{{ $o['org_address'] }}</dd></div>
    </dl>

    <h2>Izin Pengumpulan Uang atau Barang (PUB)</h2>
    <dl>
        <div><dt class="text-sm text-neutral-500">Nomor izin</dt><dd class="font-medium">{{ $o['permit_no'] }}</dd></div>
        <div><dt class="text-sm text-neutral-500">Diterbitkan oleh</dt><dd class="font-medium">{{ $o['permit_by'] }}</dd></div>
        <div><dt class="text-sm text-neutral-500">Tanggal terbit</dt><dd class="font-medium">{{ $o['permit_date'] }}</dd></div>
        <div><dt class="text-sm text-neutral-500">Masa berlaku</dt><dd class="font-medium">{{ $o['permit_until'] }}</dd></div>
    </dl>

    <h2>Dasar hukum</h2>
    <ul>
        <li>Undang-Undang Nomor 9 Tahun 1961 tentang Pengumpulan Uang atau Barang</li>
        <li>Peraturan Pemerintah Nomor 29 Tahun 1980 tentang Pelaksanaan Pengumpulan Sumbangan</li>
        <li>Peraturan Menteri Sosial Nomor 8 Tahun 2021 tentang Penyelenggaraan Pengumpulan Uang atau Barang, sebagaimana diubah dengan Peraturan Menteri Sosial Nomor 8 Tahun 2024</li>
        <li>Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi</li>
    </ul>

    <h2>Komitmen kami</h2>
    <ul>
        <li>Dana disalurkan sesuai tujuan kampanye yang tertera.</li>
        <li>Penggunaan dana dilaporkan dan dapat diperiksa donatur.</li>
        <li>Biaya operasional platform: {{ $o['fee_percent'] }}% dari dana terkumpul, ditampilkan terbuka.</li>
        <li>Kami melaporkan penyelenggaraan kepada pejabat pemberi izin sesuai ketentuan yang berlaku.</li>
    </ul>

    <h2>Menemukan penyimpangan?</h2>
    <p>Jika kamu menemukan kampanye yang mencurigakan atau penyalahgunaan dana, laporkan lewat <a href="{{ route('pages.kontak') }}">halaman Kontak</a>. Kami akan menindaklanjuti.</p>
</article>
@endsection
