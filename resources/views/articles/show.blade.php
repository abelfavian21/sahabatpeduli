@extends('layouts.app')
@section('title', $article->title)
@section('content')
<article class="mx-auto max-w-3xl py-10">
    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-sm text-neutral-500 transition hover:text-brand">
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg> Semua berita
    </a>
    @unless($article->published_at?->lte(now()))<p class="mt-4 rounded-xl bg-amber-100 px-4 py-2 text-sm">Pratinjau draft. Belum tampil di publik.</p>@endunless
    <h1 class="mt-4 font-display text-4xl font-extrabold leading-tight sm:text-5xl">{{ $article->title }}</h1>
    <p class="mt-3 text-sm text-neutral-500">{{ $article->published_at?->translatedFormat('d F Y') ?? 'Draft' }} · {{ $article->read_time }} menit baca</p>
    @if($article->image_url)<img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="mt-8 max-h-[28rem] w-full rounded-3xl object-cover">@endif
    <div class="mt-8 space-y-5 rounded-3xl bg-white/90 text-lg leading-relaxed text-neutral-800">
        @foreach(preg_split('/\n{2,}/', trim($article->body)) as $p)<p>{!! nl2br(e($p)) !!}</p>@endforeach
    </div>
</article>

@if($more->isNotEmpty())
<section class="mt-10">
    <h2 class="mb-6 font-display text-3xl font-extrabold">Berita lainnya</h2>
    <div class="grid gap-5 md:grid-cols-3">@foreach($more as $i => $a) @include('partials.article-card', ['a' => $a, 'i' => $i]) @endforeach</div>
</section>
@endif
@endsection
