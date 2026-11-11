@extends('layouts.app')
@section('title', $campaign->title)
@section('content')
<section class="grid gap-8 px-2 py-10 lg:grid-cols-[1fr_22rem]">
    <div>
        @if($campaign->image_url)<img src="{{ $campaign->image_url }}" alt="{{ $campaign->title }}" class="h-72 w-full rounded-3xl object-cover sm:h-96">@else<div class="grid h-64 place-items-center rounded-3xl bg-neutral-100">@include('partials.icon', ['name' => $campaign->category->icon_name, 'class' => 'h-36 w-36'])</div>@endif
        <div class="mt-5 flex gap-2 text-xs">
            <span class="rounded-full bg-neutral-100 px-3 py-1">{{ $campaign->category->name }}</span>
            @if($campaign->is_verified)<span class="rounded-full bg-lime-sp px-3 py-1">Terverifikasi</span>@endif
        </div>
        <h1 class="mt-3 text-3xl font-bold leading-tight">{{ $campaign->title }}</h1>
        <p class="mt-1 text-sm text-neutral-500">Penggalang: {{ $campaign->organizer }}</p>
        <div class="mt-6 space-y-3 leading-relaxed text-neutral-700">{!! nl2br(e($campaign->story)) !!}</div>

        <h2 class="mt-10 text-xl font-bold">Donatur terbaru</h2>
        <ul id="donor-list" class="mt-3 space-y-2">
            @forelse($donors as $d)
                <li class="rounded-2xl bg-neutral-100 p-3 text-sm">
                    <b>{{ $d->display_name }}</b> berdonasi Rp {{ number_format($d->amount, 0, ',', '.') }}
                    @if($d->message)<p class="mt-1 text-neutral-600">{{ $d->message }}</p>@endif
                </li>
            @empty <li data-empty class="text-sm text-neutral-500">Jadilah donatur pertama.</li>
            @endforelse
        </ul>
    </div>

    <aside class="reveal h-fit rounded-3xl bg-neutral-100 p-5 lg:sticky lg:top-6">
        <p id="p-collected" class="text-2xl font-bold">Rp {{ number_format($campaign->collected, 0, ',', '.') }}</p>
        <p class="text-sm text-neutral-500">terkumpul dari Rp {{ number_format($campaign->target, 0, ',', '.') }}</p>
        <div class="mt-3 h-2 overflow-hidden rounded-full bg-white"><div id="p-bar" class="bar h-full bg-brand" style="--w: {{ $campaign->percent }}%"></div></div>
        <p class="mt-2 text-sm">{{ $campaign->days_left }} hari lagi</p>

        @if(session('ok'))<p class="mt-4 rounded-2xl bg-lime-sp p-3 text-sm">{{ session('ok') }}</p>@endif

        <form id="donate-form" method="post" action="{{ route('donations.store', $campaign) }}" class="mt-5 space-y-3">
            @csrf
            <div class="flex flex-wrap gap-2">
                @foreach([25000, 50000, 100000, 250000] as $n)
                    <button type="button" onclick="document.getElementById('amount').value={{ $n }}" class="rounded-full bg-white px-3 py-1.5 text-sm">{{ number_format($n, 0, ',', '.') }}</button>
                @endforeach
            </div>
            <input id="amount" name="amount" type="number" min="10000" value="{{ old('amount') }}" placeholder="Nominal (min. Rp 10.000)" required class="w-full rounded-2xl bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
            <input name="name" value="{{ old('name') }}" placeholder="Nama" required class="w-full rounded-2xl bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email (untuk kwitansi)" class="w-full rounded-2xl bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
            <textarea name="message" rows="2" placeholder="Doa atau pesan dukungan" class="w-full rounded-2xl bg-white px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">{{ old('message') }}</textarea>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_anonymous" value="1"> Donasi sebagai Hamba Allah</label>
            @if($errors->any())<p class="text-sm text-red-600">{{ $errors->first() }}</p>@endif
            <p id="donate-msg" class="text-sm"></p>
            <button id="donate-btn" class="w-full rounded-full bg-brand py-3 font-medium text-white disabled:opacity-60">Lanjut ke pembayaran</button>
            <p class="text-center text-xs text-neutral-500">QRIS, transfer bank (VA), dan e-wallet via Midtrans.</p>
        </form>
    </aside>
</section>
<script src="{{ config('midtrans.snap_js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
(() => {
    const f = document.getElementById('donate-form'), b = document.getElementById('donate-btn'), m = document.getElementById('donate-msg');
    const col = document.getElementById('p-collected'), bar = document.getElementById('p-bar'), list = document.getElementById('donor-list');
    const syncTpl = @json(route('donations.sync', '__ID__'));
    const money = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
    let shown = {{ (int) $campaign->collected }};

    const say = (t, ok) => { m.textContent = t; m.className = 'text-sm ' + (ok ? 'text-emerald-700' : 'text-red-600'); };
    const tween = to => {
        const from = shown, t0 = performance.now(); shown = to;
        (function tick(t) { const p = Math.min(1, (t - t0) / 900);
            col.textContent = money(from + (to - from) * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(tick); })(t0);
    };
    const addDonor = d => {
        list.querySelector('[data-empty]')?.remove();
        const li = document.createElement('li'), name = document.createElement('b');
        li.className = 'rounded-2xl bg-neutral-100 p-3 text-sm'; name.textContent = d.name;
        li.append(name, ' berdonasi ' + money(d.amount));
        if (d.message) { const p = document.createElement('p'); p.className = 'mt-1 text-neutral-600'; p.textContent = d.message; li.append(p); }
        list.prepend(li);
    };
    // Cek status ke server tiap 4 detik (maks. 1 menit): progres naik begitu pembayaran terkonfirmasi.
    const sync = async orderId => {
        for (let i = 0; i < 15; i++) {
            try {
                const j = await (await fetch(syncTpl.replace('__ID__', orderId), { headers: { Accept: 'application/json' } })).json();
                if (j.collected !== shown) { tween(j.collected); bar.style.setProperty('--w', j.percent + '%'); }
                if (j.status === 'paid') { addDonor(j.donor); say('Terima kasih! Donasimu sudah masuk.', true); return; }
                if (j.status === 'failed' || j.status === 'expired') { say('Pembayaran tidak selesai.'); return; }
            } catch (e) {}
            await new Promise(r => setTimeout(r, 4000));
        }
    };
    const finish = (orderId, msg) => { f.reset(); b.disabled = false; say(msg, true); sync(orderId); };

    f.addEventListener('submit', async e => {
        e.preventDefault(); b.disabled = true; say('');
        try {
            const res = await fetch(f.action, { method: 'POST', body: new FormData(f), headers: { Accept: 'application/json' } });
            const j = await res.json();
            if (!res.ok) { say(j.message || 'Gagal membuat pembayaran.'); b.disabled = false; return; }
            snap.pay(j.token, {
                onSuccess: () => finish(j.order_id, 'Pembayaran diterima. Memperbarui progres...'),
                onPending: () => finish(j.order_id, 'Menunggu pembayaran. Progres naik setelah dibayar.'),
                onError: () => { say('Pembayaran gagal. Coba lagi.'); b.disabled = false; },
                onClose: () => { b.disabled = false; }
            });
        } catch (err) { say('Koneksi bermasalah. Coba lagi.'); b.disabled = false; }
    });
})();
</script>
@endsection
