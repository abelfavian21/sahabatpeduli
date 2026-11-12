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
        <a href="{{ route('campaigns.index') }}" class="rounded-full px-5 py-2.5 text-sm font-medium ring-1 ring-black/10 transition hover:bg-ink hover:text-white">Semua kampanye</a>
    </div>
    @php
        $tint = ['bencana' => 'from-sky-100 to-sky-300', 'pendidikan' => 'from-amber-100 to-amber-300', 'kesehatan' => 'from-rose-100 to-rose-300', 'kemanusiaan' => 'from-emerald-100 to-emerald-300'];
        $desc = ['bencana' => 'Banjir, longsor, gempa. Bantuan cepat untuk korban terdampak.', 'pendidikan' => 'Beasiswa, perlengkapan sekolah, dan akses belajar untuk anak.', 'kesehatan' => 'Biaya berobat dan operasi bagi yang tak mampu.', 'kemanusiaan' => 'Pangan dan kebutuhan dasar bagi sesama.'];
    @endphp
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:auto-rows-[13.5rem]">
        @foreach($categories as $i => $cat)
            @php
                $size = $i === 0 ? 'big' : ($i === 1 ? 'wide' : 'small');
                $span = ['big' => 'sm:col-span-2 lg:row-span-2', 'wide' => 'sm:col-span-2', 'small' => ''][$size];
                $icon = ['big' => 'h-52 w-52 right-4 bottom-4 sm:h-64 sm:w-64', 'wide' => 'h-32 w-32 right-8 top-1/2 -translate-y-1/2', 'small' => 'h-20 w-20 right-4 top-4'][$size];
            @endphp
            <div class="reveal {{ $span }}" style="--d: {{ $i * 100 }}ms">
                <a href="{{ route('campaigns.index', ['category' => $cat->slug]) }}"
                   class="group relative flex h-full min-h-[13.5rem] flex-col justify-between overflow-hidden rounded-[2rem] bg-gradient-to-br {{ $tint[$cat->slug] ?? 'from-neutral-100 to-neutral-300' }} p-6 transition duration-300 ease-sp hover:-translate-y-1 hover:shadow-2xl">
                    <span class="absolute -right-10 -top-10 h-44 w-44 rounded-full bg-white/40"></span>
                    <span class="absolute -bottom-14 -left-10 h-40 w-40 rounded-full bg-white/25"></span>
                    <span class="absolute transition duration-500 ease-sp group-hover:-rotate-3 group-hover:scale-105 {{ $icon }}">@include('partials.icon', ['name' => $cat->icon_name, 'class' => 'h-full w-full drop-shadow-xl'])</span>

                    <div class="relative">
                        <h3 class="font-display font-extrabold {{ $size === 'big' ? 'text-4xl' : 'text-2xl' }}">{{ $cat->name }}</h3>
                        @if($size !== 'small')<p class="mt-2 max-w-[15rem] text-sm text-ink/70">{{ $desc[$cat->slug] ?? '' }}</p>@endif
                    </div>
                    <div class="relative flex items-end justify-between gap-3">
                        <div>
                            <span class="rounded-full bg-ink px-3 py-1 text-xs text-white">{{ $cat->campaigns_count }} kampanye aktif</span>
                            @if($size === 'big' && $cat->raised)<p class="mt-3 text-sm"><b>Rp {{ number_format($cat->raised, 0, ',', '.') }}</b> <span class="text-ink/70">terkumpul</span></p>@endif
                        </div>
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white text-ink transition duration-500 ease-sp group-hover:rotate-45 group-hover:bg-ink group-hover:text-white">
                            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                        </span>
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
    <div class="relative mt-9 inline-block"><a href="{{ route('campaigns.index') }}" class="inline-block rounded-full bg-ink px-9 py-4 font-medium text-white transition hover:bg-brand">Mulai berdonasi</a></div>
</section>
@endsection
