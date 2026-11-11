@extends('admin.layout')
@section('title', 'Donasi')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <h1 class="text-2xl font-bold">Donasi</h1>
    <div class="flex gap-2 text-sm">
        @foreach(['' => 'Semua', 'pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $v => $l)
            <a href="{{ route('admin.donations.index', $v ? ['status' => $v] : []) }}" class="rounded-full px-4 py-2 {{ request('status', '') === $v ? 'bg-neutral-900 text-white' : 'bg-white' }}">{{ $l }}</a>
        @endforeach
    </div>
</div>
<div class="overflow-x-auto rounded-2xl bg-white">
    <table class="w-full text-left text-sm">
        <thead class="border-b text-neutral-500"><tr><th class="p-4">Donatur</th><th>Kampanye</th><th>Nominal</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody class="divide-y">
        @forelse($donations as $d)
            <tr>
                <td class="p-4"><b>{{ $d->name }}</b><p class="text-xs text-neutral-500">{{ $d->email }}</p><p class="text-xs text-neutral-400">{{ $d->order_id }}{{ $d->payment_type ? ' · '.$d->payment_type : '' }}</p></td>
                <td>{{ $d->campaign->title }}</td>
                <td>Rp {{ number_format($d->amount, 0, ',', '.') }}</td>
                <td>{{ $d->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form method="post" action="{{ route('admin.donations.update', $d) }}">@csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="rounded-full bg-neutral-100 px-3 py-1.5 text-xs">
                            @foreach(['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa'] as $v => $l)<option value="{{ $v }}" @selected($d->status === $v)>{{ $l }}</option>@endforeach
                        </select>
                    </form>
                </td>
            </tr>
        @empty <tr><td colspan="5" class="p-6 text-neutral-500">Belum ada donasi.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $donations->links() }}</div>
@endsection
