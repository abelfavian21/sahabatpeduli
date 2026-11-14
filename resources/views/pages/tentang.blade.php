@extends('layouts.app')
@section('title', 'Tentang kami')
@php $o = config('sahabat'); @endphp
@section('content')
<article class="legal mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Tentang Sahabat Peduli</h1>
    <p class="!text-sm !text-neutral-500">Terakhir diperbarui: {{ $o['updated'] }}</p>

    <p>Sahabat Peduli adalah ruang untuk saling peduli: menggalang donasi bagi korban bencana, pendidikan, kesehatan, dan sesama yang membutuhkan, serta membagikan kabar kemanusiaan.</p>
    <p>Platform ini diselenggarakan oleh <b>{{ $o['org_name'] }}</b>. Detail legalitas ada di <a href="{{ route('pages.legalitas') }}">halaman Legalitas &amp; izin</a>.</p>

    <h2>Cara kerja</h2>
    <ol>
        <li><b>Pengajuan.</b> Siapa pun dapat mengajukan kampanye lewat formulir. Pengaju tidak membuat kampanye sendiri.</li>
        <li><b>Verifikasi.</b> Tim kami memeriksa identitas, cerita, dan dokumen pendukung. Kami dapat menghubungi pengaju untuk informasi tambahan.</li>
        <li><b>Tayang.</b> Kampanye yang lolos dipublikasikan dengan penanda terverifikasi.</li>
        <li><b>Donasi.</b> Donatur berdonasi lewat QRIS, transfer bank, atau e-wallet melalui Midtrans.</li>
        <li><b>Pelaporan.</b> Dana disalurkan kepada penerima dan penggunaannya dilaporkan.</li>
    </ol>

    <h2>Nilai kami</h2>
    <ul>
        <li>Transparan: dana dan penggunaannya dapat ditelusuri.</li>
        <li>Selektif: kampanye diverifikasi sebelum tayang.</li>
        <li>Menjaga martabat: kami menghormati penerima bantuan dan data pribadi mereka.</li>
    </ul>
    <p><a href="{{ route('requests.create') }}">Ajukan kampanye</a> · <a href="{{ route('pages.kontak') }}">Hubungi kami</a></p>
</article>
@endsection
