@extends('admin.layout')
@section('title', 'Berita')
@section('content')
<div class="mb-6 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Berita</h1>
    <a href="{{ route('admin.articles.create') }}" class="rounded-full bg-brand px-5 py-2.5 text-sm font-medium text-white">Tulis berita</a>
</div>
<div class="overflow-x-auto rounded-2xl bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b text-neutral-500"><tr><th class="p-4">Berita</th><th>Status</th><th>Tanggal</th><th></th></tr></thead>
        <tbody class="divide-y">
        @forelse($articles as $a)
            <tr>
                <td class="flex items-center gap-3 p-4">
                    @if($a->image_url)<img src="{{ $a->image_url }}" class="h-12 w-16 rounded-xl object-cover" alt="">@else<span class="h-12 w-16 rounded-xl bg-neutral-100"></span>@endif
                    <span class="font-medium">{{ $a->title }}</span>
                </td>
                <td><span class="rounded-full px-3 py-1 text-xs {{ $a->published_at ? 'bg-lime-sp' : 'bg-neutral-100' }}">{{ $a->published_at ? 'Terbit' : 'Draft' }}</span></td>
                <td>{{ ($a->published_at ?? $a->created_at)->format('d/m/Y') }}</td>
                <td class="space-x-3 whitespace-nowrap p-4 text-right">
                    <a href="{{ route('articles.show', $a) }}" target="_blank" class="text-neutral-500">Lihat</a>
                    <a href="{{ route('admin.articles.edit', $a) }}" class="text-brand">Ubah</a>
                    <form method="post" action="{{ route('admin.articles.destroy', $a) }}" class="inline" onsubmit="return confirm('Hapus berita ini?')">@csrf @method('DELETE')<button class="text-red-600">Hapus</button></form>
                </td>
            </tr>
        @empty <tr><td colspan="4" class="p-6 text-neutral-500">Belum ada berita.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $articles->links() }}</div>
@endsection
