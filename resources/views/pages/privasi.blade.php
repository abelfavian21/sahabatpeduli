@extends('layouts.app')
@section('title', 'Kebijakan privasi')
@php $o = config('sahabat'); @endphp
@section('content')
<article class="legal mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Kebijakan Privasi</h1>
    <p class="!text-sm !text-neutral-500">Terakhir diperbarui: {{ $o['updated'] }}</p>

    <p>{{ $o['org_name'] }} (“kami”) menyelenggarakan Sahabat Peduli. Kebijakan ini menjelaskan data pribadi apa yang kami kumpulkan, untuk apa, dan hakmu atas data itu, sesuai Undang-Undang Pelindungan Data Pribadi.</p>

    <h2>Data yang kami kumpulkan</h2>
    <h3>Donatur</h3>
    <ul><li>Nama, email (opsional), nominal donasi, dan pesan atau doa.</li><li>Data transaksi dari Midtrans, seperti metode dan status pembayaran. Kami tidak menyimpan nomor kartu atau PIN.</li></ul>
    <h3>Pengaju kampanye</h3>
    <ul><li>Nama, NIK, nomor WhatsApp, email (opsional), dan domisili.</li><li>Foto KTP, foto selfie (jika diunggah), serta dokumen pendukung.</li><li>Isi pengajuan: cerita, lokasi, kebutuhan dana, dan rencana penggunaan dana.</li></ul>
    <h3>Teknis</h3>
    <ul><li>Alamat IP dan cookie sesi yang diperlukan agar situs berfungsi dan aman.</li></ul>

    <h2>Untuk apa data dipakai</h2>
    <ul>
        <li>Memproses dan mengonfirmasi donasi.</li>
        <li>Memverifikasi identitas dan kebenaran pengajuan kampanye.</li>
        <li>Menghubungi pengaju terkait pengajuannya.</li>
        <li>Pelaporan, audit, pencegahan penipuan, dan memenuhi kewajiban hukum.</li>
    </ul>
    <p>Dasar pemrosesan adalah persetujuanmu, pelaksanaan layanan yang kamu minta, dan kewajiban hukum kami.</p>

    <h2>Apa yang ditampilkan ke publik</h2>
    <p>Nama donatur dan pesannya tampil di halaman kampanye. Pilih “Hamba Allah” jika ingin anonim. <b>KTP, NIK, selfie, dan dokumen pengaju tidak pernah ditampilkan ke publik.</b></p>

    <h2>Perlindungan data</h2>
    <ul>
        <li>Berkas identitas disimpan di penyimpanan tertutup dan hanya dapat dibuka tim verifikasi yang berwenang.</li>
        <li>NIK disimpan dalam bentuk terenkripsi.</li>
        <li>Pembayaran diproses oleh Midtrans, penyedia pembayaran berlisensi.</li>
    </ul>
    <p>Tidak ada sistem yang sepenuhnya kebal. Jika terjadi insiden yang berdampak pada datamu, kami akan memberi tahu sesuai ketentuan hukum.</p>

    <h2>Berbagi data</h2>
    <p>Kami tidak menjual data pribadi. Data dibagikan hanya kepada penyedia pembayaran (Midtrans) untuk memproses donasi, serta kepada pihak berwenang jika diwajibkan hukum.</p>

    <h2>Lama penyimpanan</h2>
    <p>Data donasi disimpan selama dibutuhkan untuk pelaporan dan kewajiban hukum. Berkas identitas pengajuan yang ditolak dihapus paling lama {{ $o['retention_days'] }} hari setelah keputusan.</p>

    <h2>Hakmu</h2>
    <p>Kamu berhak meminta akses, perbaikan, atau penghapusan datamu, serta menarik persetujuan, sepanjang tidak bertentangan dengan kewajiban hukum kami. Kirim permintaan ke <a href="mailto:{{ $o['privacy_email'] }}">{{ $o['privacy_email'] }}</a>.</p>

    <h2>Anak di bawah umur</h2>
    <p>Layanan ini ditujukan bagi pengguna dewasa. Jika kampanye menyangkut anak, foto dan datanya hanya dipublikasikan dengan izin orang tua atau walinya.</p>

    <h2>Perubahan kebijakan</h2>
    <p>Kebijakan ini dapat diperbarui. Tanggal pembaruan terakhir tercantum di atas.</p>
</article>
@endsection
