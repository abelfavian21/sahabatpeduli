@extends('layouts.app')
@section('title', 'Kabar kemanusiaan')
@section('content')
<section class="py-10">
    <h1 class="font-display text-4xl font-extrabold sm:text-6xl">Kabar kemanusiaan</h1>
    <p class="mt-3 max-w-xl text-neutral-600">Cerita, laporan, dan kabar terbaru dari lapangan.</p>
    <div class="mt-10 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse($articles as $i => $a) @include('partials.article-card', ['a' => $a, 'i' => $i % 3])
        @empty <p class="text-neutral-500 md:col-span-3">Belum ada berita.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $articles->links() }}</div>
</section>
@endsection
