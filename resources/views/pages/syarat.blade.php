@extends('layouts.app')
@section('title', 'Syarat dan ketentuan')
@php $o = config('sahabat'); @endphp
@section('content')
<article class="legal mx-auto max-w-3xl py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-5xl">Syarat &amp; Ketentuan</h1>
    <p class="!text-sm !text-neutral-500">Terakhir diperbarui: {{ $o['updated'] }}</p>

    <p>Dengan memakai Sahabat Peduli, kamu menyetujui syarat berikut. Platform ini diselenggarakan oleh {{ $o['org_name'] }}.</p>

    <h2>1. Donasi</h2>
    <ul>
        <li>Donasi bersifat sukarela. Nominal minimum Rp 10.000.</li>
        <li>Pembayaran diproses oleh Midtrans. Donasi dihitung masuk setelah pembayaran terkonfirmasi.</li>
        <li>Donasi pada dasarnya tidak dapat ditarik kembali. Pengecualian: kesalahan transaksi, pembayaran ganda, atau kampanye yang terbukti menyimpang. Hubungi kami di <a href="{{ route('pages.kontak') }}">halaman Kontak</a> untuk meminta peninjauan.</li>
        <li>Biaya operasional platform sebesar {{ $o['fee_percent'] }}% dari dana terkumpul.</li>
    </ul>

    <h2>2. Pengajuan kampanye</h2>
    <ul>
        <li>Pengaju tidak membuat kampanye sendiri. Pengaju mengirim tiket pengajuan, dan tim kami yang memproses serta membuat kampanye jika disetujui.</li>
        <li>Pengaju wajib memberikan data yang benar dan lengkap, termasuk identitas sesuai KTP.</li>
        <li>Kami berhak meminta informasi tambahan, menolak pengajuan, mengubah isi agar sesuai standar, menunda, atau menghentikan kampanye kapan saja jika ada ketidaksesuaian atau dugaan penyimpangan.</li>
        <li>Pengajuan tidak menjamin kampanye disetujui.</li>
        <li>Pengaju dan penerima wajib menggunakan dana sesuai rencana yang diajukan, dan memberikan bukti serta laporan penggunaan dana.</li>
    </ul>

    <h2>3. Larangan</h2>
    <ul>
        <li>Data atau cerita palsu, menyesatkan, atau memakai identitas orang lain.</li>
        <li>Konten bermuatan SARA, kekerasan, pornografi, atau melanggar hukum.</li>
        <li>Penggalangan dana untuk tujuan di luar yang dinyatakan.</li>
        <li>Mengganggu keamanan atau pembayaran situs.</li>
    </ul>

    <h2>4. Pelaporan dan penghentian</h2>
    <p>Siapa pun dapat melaporkan kampanye mencurigakan lewat halaman Kontak. Kampanye yang terbukti menyimpang dapat dihentikan, dan dana yang tersisa diperlakukan sesuai ketentuan hukum dan keputusan tim kami.</p>

    <h2>5. Konten dan hak kekayaan intelektual</h2>
    <p>Pengaju menjamin memiliki hak atas foto, video, dan tulisan yang diunggah, serta izin dari orang yang tampil di dalamnya. Dengan mengunggah, pengaju mengizinkan kami menampilkannya di platform.</p>

    <h2>6. Batasan tanggung jawab</h2>
    <p>Kami berupaya memverifikasi kampanye, namun tidak dapat menjamin setiap informasi dari pengaju. Kami tidak bertanggung jawab atas gangguan di luar kendali kami, seperti gangguan jaringan atau penyedia pembayaran.</p>

    <h2>7. Privasi</h2>
    <p>Pemrosesan data pribadi diatur dalam <a href="{{ route('pages.privasi') }}">Kebijakan Privasi</a>.</p>

    <h2>8. Hukum yang berlaku</h2>
    <p>Syarat ini tunduk pada hukum Republik Indonesia. Perselisihan diupayakan diselesaikan secara musyawarah terlebih dahulu.</p>

    <h2>9. Perubahan</h2>
    <p>Syarat dapat diperbarui. Penggunaan situs setelah pembaruan berarti kamu menyetujui perubahan itu.</p>
</article>
@endsection
