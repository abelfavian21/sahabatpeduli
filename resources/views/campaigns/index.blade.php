@extends('layouts.app')
@section('title', 'Kampanye')
@section('content')
<section class="px-2 py-10">
    <h1 class="text-4xl font-bold">Semua kampanye</h1>
    <form method="get" class="mt-6 flex flex-wrap items-center gap-2">
        <input name="q" value="{{ request('q') }}" placeholder="Cari kampanye" class="rounded-full bg-neutral-100 px-5 py-2.5 text-sm outline-none focus:ring-2 focus:ring-brand">
        <a href="{{ route('campaigns.index') }}" class="rounded-full px-4 py-2 text-sm {{ request('category') ? 'bg-neutral-100' : 'bg-ink text-white' }}">Semua</a>
        @foreach($categories as $cat)
            <a href="{{ route('campaigns.index', ['category' => $cat->slug]) }}"
               class="rounded-full px-4 py-2 text-sm {{ request('category') === $cat->slug ? 'bg-ink text-white' : 'bg-neutral-100' }}">{{ $cat->name }}</a>
        @endforeach
    </form>
    <div class="mt-8 grid gap-4 md:grid-cols-3">
        @forelse($campaigns as $c) @include('partials.campaign-card', ['c' => $c])
        @empty <p class="col-span-3 text-neutral-500">Belum ada kampanye yang cocok. Coba kata kunci atau kategori lain.</p>
        @endforelse
    </div>
    <div class="mt-8">{{ $campaigns->links() }}</div>
</section>
@endsection
