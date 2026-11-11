@extends('admin.layout')
@section('title', 'Kampanye')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Kampanye</h1>
    <a href="{{ route('admin.campaigns.create') }}" class="rounded-full bg-brand px-5 py-2.5 text-sm font-medium text-white">Tambah kampanye</a>
</div>
<div class="overflow-x-auto rounded-2xl bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b text-neutral-500"><tr><th class="p-4">Kampanye</th><th>Kategori</th><th>Terkumpul</th><th>Status</th><th></th></tr></thead>
        <tbody class="divide-y">
        @forelse($campaigns as $c)
            <tr>
                <td class="flex items-center gap-3 p-4">
                    @if($c->image_url)<img src="{{ $c->image_url }}" class="h-12 w-12 rounded-xl object-cover" alt="">@else<span class="h-12 w-12 rounded-xl bg-neutral-100"></span>@endif
                    <div><a href="{{ route('campaigns.show', $c) }}" target="_blank" class="font-medium hover:text-brand">{{ $c->title }}</a>
                        <p class="text-xs text-neutral-500">{{ $c->organizer }}</p></div>
                </td>
                <td>{{ $c->category->name }}</td>
                <td>Rp {{ number_format($c->collected, 0, ',', '.') }} <span class="text-neutral-400">/ {{ number_format($c->target, 0, ',', '.') }}</span></td>
                <td><span class="rounded-full bg-neutral-100 px-3 py-1 text-xs">{{ $c->status }}</span>@if($c->is_urgent) <span class="rounded-full bg-neutral-900 px-3 py-1 text-xs text-white">mendesak</span>@endif</td>
                <td class="space-x-3 whitespace-nowrap p-4 text-right">
                    <a href="{{ route('admin.campaigns.edit', $c) }}" class="text-brand">Ubah</a>
                    <form method="post" action="{{ route('admin.campaigns.destroy', $c) }}" class="inline" onsubmit="return confirm('Hapus kampanye ini? Donasi terkait ikut terhapus.')">@csrf @method('DELETE')
                        <button class="text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty <tr><td colspan="5" class="p-6 text-neutral-500">Belum ada kampanye.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $campaigns->links() }}</div>
@endsection
