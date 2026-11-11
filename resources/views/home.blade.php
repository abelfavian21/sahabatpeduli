@extends('layouts.app')
@section('content')

{{-- Hero --}}
<section class="relative grid min-h-[calc(100svh-6rem)] items-center gap-10 py-12 lg:grid-cols-[1.05fr_.95fr]">
    <div class="morph drift absolute -left-24 top-10 -z-10 h-72 w-72 bg-lime-sp/70 blur-2xl"></div>
    <div>
        <h1 class="font-display text-[clamp(2.6rem,6.4vw,6rem)] font-extrabold leading-[.98] tracking-tight">
            @foreach(['Peduli','itu','sederhana.','/','Mulai','dari','satu','donasi.'] as $k => $wd)
                @if($wd === '/')<br>@else<span class="w mr-[.22em]"><span style="--d: {{ $k * 70 }}ms">{{ $wd }}</span></span>@endif
            @endforeach
        </h1>
        <p class="rise mt-6 max-w-lg text-lg text-neutral-600" style="--d: 700ms">Galang dan salurkan bantuan untuk korban bencana, pendidikan, dan sesama yang membutuhkan. Semua dana bisa dilacak.</p>
        <div class="rise mt-9 flex flex-wrap items-center gap-4" style="--d: 850ms">
            <div class="inline-block">
                <a href="{{ route('campaigns.index') }}" class="group inline-flex items-center gap-3 rounded-full bg-brand py-2 pl-7 pr-2 font-medium text-white shadow-xl shadow-brand/30 transition duration-300 hover:bg-blue-600">
                    Pilih kampanye
                    <span class="grid h-11 w-11 place-items-center rounded-full bg-white text-brand transition duration-500 ease-sp group-hover:rotate-45">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </a>
            </div>
            <a href="#berita" class="rounded-full px-6 py-3.5 font-medium ring-1 ring-black/10 transition duration-300 hover:bg-neutral-100">Baca kabar</a>
        </div>
        <dl class="rise mt-12 grid max-w-xl grid-cols-3 gap-4" style="--d: 1000ms">
            <div><dd class="font-display text-2xl font-extrabold sm:text-3xl"><span data-count="{{ $stats['collected'] }}" data-prefix="Rp ">Rp {{ number_format($stats['collected'], 0, ',', '.') }}</span></dd><dt class="text-sm text-neutral-500">terkumpul</dt></div>
            <div><dd class="font-display text-2xl font-extrabold sm:text-3xl"><span data-count="{{ $stats['campaigns'] }}">{{ $stats['campaigns'] }}</span></dd><dt class="text-sm text-neutral-500">kampanye aktif</dt></div>
            <div><dd class="font-display text-2xl font-extrabold sm:text-3xl"><span data-count="{{ $stats['donors'] }}">{{ $stats['donors'] }}</span></dd><dt class="text-sm text-neutral-500">donatur</dt></div>
        </dl>
    </div>

    {{-- Visual orbit --}}
    <div class="float relative mx-auto w-full" style="--d: 300ms; --s: min(86vw, 600px); max-width: var(--s); aspect-ratio: 1">
        <div class="morph absolute inset-[4%] bg-lime-deep"></div>
        <div class="spin-slow absolute inset-[14%] rounded-full border-2 border-dashed border-ink/20"></div>
        <div class="spin-rev absolute inset-[26%] rounded-full border-2 border-dashed border-white/70"></div>
        <div class="absolute inset-[30%] grid place-items-center rounded-full bg-brand shadow-2xl shadow-brand/40">
            <span class="pulse-soft">@include('partials.icon', ['name' => 'heart', 'class' => 'h-[70%] w-[70%] min-h-[6rem] min-w-[6rem] mx-auto drop-shadow-xl'])</span>
        </div>
        <div class="orbit absolute inset-0">
            @foreach(['wave', 'book', 'people', 'heart'] as $k => $ic)
                @php $a = $k * 90 + 20; @endphp
                <div class="absolute left-1/2 top-1/2 -ml-9 -mt-9 h-[4.5rem] w-[4.5rem]" style="transform: rotate({{ $a }}deg) translateX(calc(var(--s) * .4)) rotate({{ -$a }}deg)">
                    <div class="counter grid h-full w-full place-items-center rounded-2xl bg-white shadow-xl ring-1 ring-black/5">@include('partials.icon', ['name' => $ic, 'class' => 'h-11 w-11'])</div>
                </div>
            @endforeach
        </div>
        <div id="transparansi" class="absolute -bottom-2 left-0 right-0 h-24 sm:left-[-8%] sm:right-auto sm:w-72">
            @foreach($campaigns->take(1) as $i => $c)
                <div class="absolute inset-x-0 bottom-0 flex items-center gap-3 rounded-2xl bg-white p-3 shadow-2xl ring-1 ring-black/5" style="--d: {{ $i * 3 }}s">
                    <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-neutral-100">@include('partials.icon', ['name' => $c->category->icon_name, 'class' => 'h-8 w-8'])</span>
                    <div class="min-w-0 text-sm"><p class="truncate font-semibold">{{ $c->title }}</p><p class="text-neutral-500">{{ $c->percent }}% terkumpul · {{ $c->days_left }} hari lagi</p></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Bidang --}}
<section class="reveal relative mt-14 overflow-hidden rounded-[2.5rem] bg-brand p-6 sm:p-12">
    <span class="absolute -right-20 -top-20 h-72 w-72 rounded-full bg-white/10"></span>
    <span class="absolute -bottom-24 left-1/4 h-80 w-80 rounded-full bg-lime-deep/20" style="animation-delay:-5s"></span>
    <h2 class="relative mb-10 font-display text-4xl font-extrabold text-white sm:text-5xl">Bidang kepedulian</h2>
    <div class="relative grid grid-cols-2 gap-4 pb-6 lg:grid-cols-4 lg:gap-6">
        @foreach($categories as $i => $cat)
            <div class="reveal {{ $i % 2 ? 'lg:mt-10' : '' }}" style="--d: {{ $i * 120 }}ms">
                <a href="{{ route('campaigns.index', ['category' => $cat->slug]) }}"
                   class="relative block h-60 rounded-[2rem] bg-white p-5 shadow-xl transition duration-300 ease-sp hover:-translate-y-1 hover:shadow-2xl">
                    <svg viewBox="0 0 24 24" class="absolute -top-3 right-5 h-9 w-9 text-neutral-300" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="m8 7 6-4a3 3 0 0 1 4 4l-9 11a2 2 0 0 1-3-3l8-9"/></svg>
                    <span class="font-display text-2xl font-extrabold">{{ $cat->name }}</span>
                    <span class="ic absolute bottom-4 right-4 transition duration-700 ease-sp">@include('partials.icon', ['name' => $cat->icon_name, 'class' => 'h-28 w-28 drop-shadow-lg'])</span>
                    <span class="absolute bottom-5 left-5 rounded-full bg-ink px-3 py-1 text-xs text-white">{{ $cat->campaigns_count }} kampanye</span>
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
        @foreach($articles as $i => $a)
            <article class="reveal" style="--d: {{ $i * 110 }}ms">
                <div class="group h-full rounded-[2rem] border border-neutral-200 p-7 transition duration-500 ease-sp hover:-translate-y-2 hover:border-brand hover:shadow-xl hover:shadow-brand/10">
                    <time class="text-xs text-neutral-500">{{ $a->published_at->translatedFormat('d F Y') }}</time>
                    <h3 class="mt-3 font-display text-2xl font-extrabold leading-tight">{{ $a->title }}</h3>
                    <p class="mt-3 text-neutral-600">{{ $a->excerpt }}</p>
                    <span class="mt-6 inline-grid h-10 w-10 place-items-center rounded-full bg-neutral-100 transition duration-500 ease-sp group-hover:rotate-45 group-hover:bg-brand group-hover:text-white">
                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                    </span>
                </div>
            </article>
        @endforeach
    </div>
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
