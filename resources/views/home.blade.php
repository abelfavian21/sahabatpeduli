@extends('layouts.app')
@section('content')

{{-- Hero --}}
<section class="grid items-center gap-10 py-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.1fr)] lg:gap-14 lg:py-16">
    <div>
        <span class="reveal inline-flex w-fit items-center gap-2 rounded-full bg-lime-sp px-4 py-1.5 text-sm font-medium">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg>
            Setiap kampanye diverifikasi
        </span>
        <h1 class="mt-6 font-display text-[clamp(2.3rem,4.6vw,4.4rem)] font-extrabold leading-[1.04] tracking-[-0.02em]">
            @foreach(['Peduli','itu','sederhana.','/','Mulai','dari','satu','donasi.'] as $k => $wd)
                @if($wd === '/')<br>@else<span class="w"><span style="--d: {{ $k * 70 }}ms">{{ $wd }}</span></span>@endif
            @endforeach
        </h1>
        <p class="reveal mt-6 max-w-lg text-lg leading-relaxed text-neutral-600" style="--d: 300ms">Galang dan salurkan bantuan untuk korban bencana, pendidikan, dan sesama yang membutuhkan. Semua dana bisa dilacak.</p>
        <div class="reveal mt-8 flex flex-wrap items-center gap-3" style="--d: 400ms">
            <a href="{{ route('campaigns.index') }}" class="group inline-flex items-center gap-3 rounded-full bg-brand py-2 pl-7 pr-2 font-medium text-white shadow-xl shadow-brand/30 transition duration-300 hover:bg-blue-600">
                Pilih kampanye
                <span class="grid h-11 w-11 place-items-center rounded-full bg-white text-brand transition duration-500 ease-sp group-hover:rotate-45">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </span>
            </a>
            <a href="#berita" class="rounded-full px-6 py-3.5 font-medium ring-1 ring-black/10 transition duration-300 hover:bg-neutral-100">Baca kabar</a>
        </div>
        <dl id="transparansi" class="reveal mt-10 flex flex-wrap gap-x-10 gap-y-5 border-t border-neutral-200 pt-6" style="--d: 500ms">
            <div><dd class="whitespace-nowrap font-display text-2xl font-extrabold">Rp {{ number_format($stats['collected'], 0, ',', '.') }}</dd><dt class="text-sm text-neutral-500">dana terkumpul</dt></div>
            <div><dd class="font-display text-2xl font-extrabold">{{ $stats['campaigns'] }}</dd><dt class="text-sm text-neutral-500">kampanye aktif</dt></div>
            <div><dd class="font-display text-2xl font-extrabold">{{ $stats['donors'] }}</dd><dt class="text-sm text-neutral-500">donatur</dt></div>
        </dl>
    </div>

    {{-- Footage: foto/video kampanye. Tanpa media, tampil ikon kategori. --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:h-[34rem] {{ $hero->count() > 1 ? 'lg:grid-rows-2' : '' }}">
        @forelse($hero as $i => $c)
            <div class="reveal {{ $i === 0 || $hero->count() === 2 ? 'sm:row-span-2' : '' }} {{ $hero->count() === 1 ? 'sm:col-span-2' : '' }}" style="--d: {{ 150 + $i * 120 }}ms">
                @include('partials.hero-media', ['c' => $c, 'big' => $i === 0 || $hero->count() === 2])
            </div>
        @empty
            <div class="grid min-h-[20rem] place-items-center rounded-[2rem] bg-neutral-100 p-10 text-center text-neutral-500 sm:col-span-2">Kampanye pertama akan tampil di sini.</div>
        @endforelse
    </div>
</section>

{{-- Bidang --}}
<section id="bidang" class="mt-10">
    <div class="reveal mb-8 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="font-display text-4xl font-extrabold sm:text-5xl">Bidang kepedulian</h2>
            <p class="mt-2 max-w-md text-neutral-600">Pilih jalur kebaikan yang paling dekat denganmu.</p>
        </div>
        <a href="{{ route('campaigns.index') }}" class="rounded-full bg-white px-5 py-2.5 text-sm font-medium ring-1 ring-black/10 transition hover:bg-ink hover:text-white">Semua kampanye</a>
    </div>
    @php
        $wipe = ['bencana' => 'bg-sky-200', 'pendidikan' => 'bg-amber-200', 'kesehatan' => 'bg-rose-200', 'kemanusiaan' => 'bg-emerald-200'];
        $desc = ['bencana' => 'Banjir, longsor, gempa. Bantuan cepat untuk korban terdampak.', 'pendidikan' => 'Beasiswa, perlengkapan sekolah, dan akses belajar untuk anak.', 'kesehatan' => 'Biaya berobat dan operasi bagi yang tak mampu.', 'kemanusiaan' => 'Pangan dan kebutuhan dasar bagi sesama.'];
    @endphp
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($categories as $i => $cat)
            <div class="reveal" style="--d: {{ $i * 90 }}ms">
                <a href="{{ route('campaigns.index', ['category' => $cat->slug]) }}"
                   class="group relative flex h-full min-h-[15rem] flex-col justify-between overflow-hidden rounded-[1.75rem] bg-white p-6 ring-1 ring-black/5 transition duration-300 hover:shadow-xl">
                    {{-- sapuan warna dari bawah saat hover --}}
                    <span class="absolute inset-0 origin-bottom scale-y-0 {{ $wipe[$cat->slug] ?? 'bg-neutral-200' }} transition duration-500 ease-sp group-hover:scale-y-100"></span>

                    <div class="relative flex items-start justify-between">
                        <span class="grid h-12 w-12 place-items-center rounded-2xl bg-neutral-100 transition duration-500 ease-sp group-hover:-rotate-6 group-hover:scale-110 group-hover:bg-white/80">
                            @include('partials.icon', ['name' => $cat->icon_name, 'class' => 'h-7 w-7'])
                        </span>
                        <span class="grid h-10 w-10 place-items-center rounded-full ring-1 ring-black/10 transition duration-500 ease-sp group-hover:rotate-45 group-hover:bg-ink group-hover:text-white group-hover:ring-ink">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                        </span>
                    </div>

                    <div class="relative mt-8">
                        {{-- teks bergulir: salinan pertama keluar, salinan kedua masuk --}}
                        <div class="h-9 overflow-hidden font-display text-3xl font-extrabold leading-9">
                            <span class="block transition duration-500 ease-sp group-hover:-translate-y-full">{{ $cat->name }}</span>
                            <span class="block transition duration-500 ease-sp group-hover:-translate-y-full" aria-hidden="true">{{ $cat->name }}</span>
                        </div>
                        <p class="mt-2 line-clamp-2 text-sm text-neutral-600 transition duration-300 group-hover:text-ink">{{ $cat->description ?: ($desc[$cat->slug] ?? '') }}</p>
                        <p class="mt-4 flex items-center gap-2 text-xs font-medium">
                            <span class="rounded-full bg-ink px-2.5 py-1 text-white">{{ $cat->campaigns_count }} kampanye</span>
                            <span class="text-neutral-500 transition group-hover:text-ink">Rp {{ number_format($cat->raised ?? 0, 0, ',', '.') }}</span>
                        </p>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</section>

{{-- Kampanye --}}
<section class="mt-24">
    <div class="reveal mb-8 flex items-end justify-between">
        <h2 class="font-display text-4xl font-extrabold sm:text-5xl">Kampanye terbaru</h2>
        <a href="{{ route('campaigns.index') }}" class="rounded-full px-5 py-2.5 text-sm font-medium ring-1 ring-black/10 transition hover:bg-ink hover:text-white">Semua kampanye</a>
    </div>
    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($campaigns as $i => $c) @include('partials.campaign-card', ['c' => $c, 'i' => $i]) @endforeach
    </div>
</section>

{{-- Berita --}}
<section id="berita" class="mt-24">
    <h2 class="reveal mb-8 font-display text-4xl font-extrabold sm:text-5xl">Kabar kemanusiaan</h2>
    <div class="grid gap-5 md:grid-cols-3">
        @forelse($articles as $i => $a) @include('partials.article-card', ['a' => $a, 'i' => $i])
        @empty <p class="text-neutral-500 md:col-span-3">Belum ada berita.</p>
        @endforelse
    </div>
    @if($articles->isNotEmpty())
        <div class="reveal mt-10 text-center">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-3 rounded-full bg-ink py-2 pl-7 pr-2 font-medium text-white transition duration-300 hover:bg-brand">
                Lihat semua berita
                <span class="grid h-10 w-10 place-items-center rounded-full bg-white text-ink"><svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
            </a>
        </div>
    @endif
</section>

{{-- Tentang --}}
<section id="tentang" class="reveal relative mt-24 overflow-hidden rounded-[2.5rem] bg-lime-sp p-10 text-center sm:p-20">
    <span class="morph absolute -left-16 -top-16 h-64 w-64 bg-lime-deep"></span>
    <span class="morph absolute -bottom-20 -right-10 h-72 w-72 bg-white/60" style="animation-delay:-6s"></span>
    <h2 class="relative mx-auto max-w-3xl font-display text-4xl font-extrabold leading-tight sm:text-6xl">Ruang kita peduli ke sesama manusia</h2>
    <p class="relative mx-auto mt-5 max-w-xl text-lg text-neutral-700">Setiap kampanye diverifikasi tim kami. Laporan penggunaan dana dipublikasikan.</p>
    <div class="relative mt-9 flex flex-wrap justify-center gap-3"><a href="{{ route('campaigns.index') }}" class="inline-block rounded-full bg-ink px-9 py-4 font-medium text-white transition hover:bg-brand">Mulai berdonasi</a><a href="{{ route('requests.create') }}" class="inline-block rounded-full bg-white px-9 py-4 font-medium ring-1 ring-black/10 transition hover:bg-ink hover:text-white">Ajukan kampanye</a></div>
</section>
@endsection
