@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
<h1 class="mb-6 text-2xl font-bold">Dashboard</h1>
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach([['Total kampanye', $campaigns], ['Kampanye aktif', $active], ['Dana terkumpul', 'Rp '.number_format($collected, 0, ',', '.')], ['Donasi menunggu', $pending]] as [$l, $v])
        <div class="rounded-2xl bg-white p-5"><p class="text-sm text-neutral-500">{{ $l }}</p><p class="mt-1 text-2xl font-bold">{{ $v }}</p></div>
    @endforeach
</div>
<div class="mt-8 flex items-center justify-between">
    <h2 class="text-lg font-semibold">Donasi terbaru</h2>
    <a href="{{ route('admin.campaigns.create') }}" class="rounded-full bg-brand px-5 py-2.5 text-sm font-medium text-white">Tambah kampanye</a>
</div>
<div class="mt-3 divide-y rounded-2xl bg-white">
    @forelse($latest as $d)
        <div class="flex justify-between gap-3 p-4 text-sm"><span><b>{{ $d->name }}</b> · {{ $d->campaign->title }}</span><span>Rp {{ number_format($d->amount, 0, ',', '.') }} · {{ $d->status }}</span></div>
    @empty <p class="p-4 text-sm text-neutral-500">Belum ada donasi.</p>
    @endforelse
</div>
@endsection
