@extends('admin.layout')
@section('title', 'Pengajuan')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold">Pengajuan kampanye</h1>
    <div class="flex flex-wrap gap-2 text-sm">
        <a href="{{ route('admin.requests.index') }}" class="rounded-full px-4 py-2 {{ request('status') ? 'bg-white' : 'bg-neutral-900 text-white' }}">Semua</a>
        @foreach(\App\Models\CampaignRequest::STATUS as $v => $l)
            <a href="{{ route('admin.requests.index', ['status' => $v]) }}" class="rounded-full px-4 py-2 {{ request('status') === $v ? 'bg-neutral-900 text-white' : 'bg-white' }}">{{ $l }}</a>
        @endforeach
    </div>
</div>
<div class="overflow-x-auto rounded-2xl bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b text-neutral-500"><tr><th class="p-4">Tiket</th><th>Pengaju</th><th>Kampanye</th><th>Target</th><th>Status</th><th></th></tr></thead>
        <tbody class="divide-y">
        @forelse($requests as $q)
            <tr>
                <td class="p-4"><b>{{ $q->ticket_code }}</b><p class="text-xs text-neutral-500">{{ $q->created_at->format('d/m/Y H:i') }}</p></td>
                <td>{{ $q->name }}<p class="text-xs text-neutral-500">{{ $q->city }}</p></td>
                <td>{{ $q->title }}<p class="text-xs text-neutral-500">{{ $q->category?->name }}</p></td>
                <td>Rp {{ number_format($q->target, 0, ',', '.') }}</td>
                <td><span class="rounded-full px-3 py-1 text-xs {{ \App\Models\CampaignRequest::BADGE[$q->status] ?? 'bg-neutral-100' }}">{{ $q->status_label }}</span></td>
                <td class="p-4 text-right"><a href="{{ route('admin.requests.show', $q) }}" class="text-brand">Buka</a></td>
            </tr>
        @empty <tr><td colspan="6" class="p-6 text-neutral-500">Belum ada pengajuan.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $requests->links() }}</div>
@endsection
