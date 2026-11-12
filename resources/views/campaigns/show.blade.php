@extends('layouts.app')
@section('title', $campaign->title)
@php
    $tints = ['bencana' => 'from-sky-200 to-sky-400', 'pendidikan' => 'from-amber-200 to-amber-400', 'kesehatan' => 'from-rose-200 to-rose-400', 'kemanusiaan' => 'from-emerald-200 to-emerald-400'];
    $done = $campaign->collected >= $campaign->target;
    $wa = 'https://wa.me/?text=' . urlencode('Yuk bantu: ' . $campaign->title . ' ' . url()->current());
    $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none transition focus:bg-white focus:ring-2 focus:ring-brand';
@endphp
@section('content')
<div class="grid gap-8 py-8 pb-28 lg:grid-cols-[minmax(0,1fr)_26rem] lg:pb-8">

    {{-- Kiri: konten --}}
    <div>
        <a href="{{ route('campaigns.index') }}" class="mb-4 inline-flex items-center gap-2 text-sm text-neutral-500 transition hover:text-brand">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg> Semua kampanye
        </a>

        <div class="relative aspect-[16/10] max-w-2xl overflow-hidden rounded-[2rem] bg-neutral-200">
            @if($campaign->video_url)
                <video src="{{ $campaign->video_url }}" controls playsinline preload="metadata" @if($campaign->image_url) poster="{{ $campaign->image_url }}" @endif class="h-full w-full bg-black object-cover"></video>
            @elseif($campaign->image_url)
                <img src="{{ $campaign->image_url }}" alt="{{ $campaign->title }}" class="h-full w-full object-cover">
            @else
                <div class="grid h-full w-full place-items-center bg-gradient-to-br {{ $tints[$campaign->category->slug] ?? 'from-neutral-200 to-neutral-300' }}">@include('partials.icon', ['name' => $campaign->category->icon_name, 'class' => 'h-40 w-40'])</div>
            @endif
            <div class="pointer-events-none absolute inset-x-0 top-0 flex gap-2 bg-gradient-to-b from-black/40 to-transparent p-4 text-xs font-medium">
                <span class="rounded-full bg-white/90 px-3 py-1">{{ $campaign->category->name }}</span>
                @if($campaign->is_urgent)<span class="rounded-full bg-lime-deep px-3 py-1">Mendesak</span>@endif
            </div>
        </div>

        <h1 class="mt-6 font-display text-3xl font-extrabold leading-tight sm:text-5xl">{{ $campaign->title }}</h1>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-full bg-lime-deep font-display font-extrabold">{{ mb_strtoupper(mb_substr($campaign->organizer, 0, 1)) }}</span>
                <div class="text-sm">
                    <p class="text-neutral-500">Penggalang dana</p>
                    <p class="flex items-center gap-1.5 font-semibold">{{ $campaign->organizer }}
                        @if($campaign->is_verified)<svg viewBox="0 0 24 24" class="h-4 w-4 text-brand" fill="currentColor" aria-label="Terverifikasi"><path d="M12 1l2.6 2.1 3.3-.2 1 3.1 2.8 1.8-.9 3.2.9 3.2-2.8 1.8-1 3.1-3.3-.2L12 23l-2.6-2.1-3.3.2-1-3.1-2.8-1.8.9-3.2-.9-3.2 2.8-1.8 1-3.1 3.3.2z"/><path d="m8.5 12.3 2.4 2.4 4.6-4.9" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>@endif
                    </p>
                </div>
            </div>
            <div class="flex gap-2 text-sm">
                <a href="{{ $wa }}" target="_blank" rel="noopener" class="rounded-full bg-emerald-500 px-4 py-2 font-medium text-white transition hover:bg-emerald-600">Bagikan WhatsApp</a>
                <button type="button" id="copy-link" class="rounded-full px-4 py-2 font-medium ring-1 ring-black/10 transition hover:bg-neutral-100">Salin tautan</button>
            </div>
        </div>

        <section class="mt-8 rounded-[2rem] bg-white p-6 ring-1 ring-black/5 sm:p-8">
            <h2 class="font-display text-2xl font-extrabold">Cerita</h2>
            <div class="mt-4 space-y-4 text-lg leading-relaxed text-neutral-700">
                @foreach(preg_split('/\n{2,}/', trim($campaign->story)) as $para)<p>{!! nl2br(e($para)) !!}</p>@endforeach
            </div>
        </section>

        <section class="mt-6 grid gap-3 sm:grid-cols-3">
            @foreach([['Terverifikasi tim', 'Dokumen dan identitas penerima dicek sebelum tayang.'], ['Dana bisa dilacak', 'Laporan penggunaan dana dipublikasikan.'], ['Pembayaran aman', 'Diproses lewat Midtrans, kami tidak menyimpan data kartu.']] as [$t, $d])
                <div class="rounded-2xl bg-white p-4 ring-1 ring-black/5">
                    <svg viewBox="0 0 24 24" class="h-6 w-6 text-brand" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4 6v6c0 4.5 3.2 7.8 8 9 4.8-1.2 8-4.5 8-9V6z"/><path d="m9 12 2 2 4-4"/></svg>
                    <p class="mt-2 font-semibold">{{ $t }}</p><p class="mt-1 text-sm text-neutral-600">{{ $d }}</p>
                </div>
            @endforeach
        </section>

        <section class="mt-8">
            <h2 class="font-display text-2xl font-extrabold">Donatur <span id="p-donors" class="text-neutral-400">({{ $donorCount }})</span></h2>
            <ul id="donor-list" class="mt-4 space-y-3">
                @forelse($donors as $d)
                    <li class="flex gap-3 rounded-2xl bg-white p-4 ring-1 ring-black/5">
                        <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-lime-sp font-semibold">{{ mb_strtoupper(mb_substr($d->display_name, 0, 1)) }}</span>
                        <div class="min-w-0 text-sm">
                            <p><b>{{ $d->display_name }}</b> berdonasi <b class="text-brand">Rp {{ number_format($d->amount, 0, ',', '.') }}</b></p>
                            @if($d->message)<p class="mt-1 text-neutral-600">{{ $d->message }}</p>@endif
                            <p class="mt-1 text-xs text-neutral-400">{{ $d->created_at->diffForHumans() }}</p>
                        </div>
                    </li>
                @empty
                    <li data-empty class="rounded-2xl bg-white p-4 text-sm text-neutral-500 ring-1 ring-black/5">Jadilah donatur pertama.</li>
                @endforelse
            </ul>
        </section>
    </div>

    {{-- Kanan: kartu donasi --}}
    <aside id="donasi" class="h-fit scroll-mt-24 lg:sticky lg:top-24">
        <div class="rounded-[2rem] bg-white p-6 shadow-2xl shadow-brand/10 ring-1 ring-black/5">
            <div class="reveal">
                <p id="p-collected" class="font-display text-3xl font-extrabold">Rp {{ number_format($campaign->collected, 0, ',', '.') }}</p>
                <p class="text-sm text-neutral-500">terkumpul dari Rp {{ number_format($campaign->target, 0, ',', '.') }}</p>
                <div class="mt-4 h-3 overflow-hidden rounded-full bg-neutral-100"><div id="p-bar" class="bar h-full rounded-full bg-gradient-to-r from-brand to-sky-400" style="--w: {{ $campaign->percent }}%"></div></div>
                <div class="mt-3 flex justify-between text-sm">
                    <span><b id="p-percent">{{ $campaign->percent }}%</b> @if($done)<span class="rounded-full bg-lime-sp px-2 py-0.5 text-xs">Target tercapai</span>@endif</span>
                    <span class="text-neutral-500">{{ $campaign->days_left }} hari lagi</span>
                </div>
            </div>

            <form id="donate-form" method="post" action="{{ route('donations.store', $campaign) }}" class="mt-6 space-y-4 border-t border-neutral-100 pt-6">
                @csrf
                <div>
                    <p class="mb-2 text-sm font-medium">Pilih nominal</p>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach([25000 => '25rb', 50000 => '50rb', 100000 => '100rb', 250000 => '250rb', 500000 => '500rb', 1000000 => '1jt'] as $n => $l)
                            <button type="button" data-chip="{{ $n }}" class="rounded-2xl bg-white py-2.5 text-sm font-medium ring-1 ring-black/10 transition duration-200 hover:ring-brand">{{ $l }}</button>
                        @endforeach
                    </div>
                    <div class="relative mt-3">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-neutral-500">Rp</span>
                        <input id="amount-view" inputmode="numeric" autocomplete="off" placeholder="Nominal lain (min. 10.000)" class="{{ $in }} pl-11">
                        <input type="hidden" id="amount" name="amount">
                    </div>
                </div>

                <input name="name" placeholder="Nama lengkap" required class="{{ $in }}">
                <input name="email" type="email" placeholder="Email (untuk kwitansi, opsional)" class="{{ $in }}">
                <textarea name="message" rows="2" maxlength="300" placeholder="Doa atau pesan dukungan (opsional)" class="{{ $in }}"></textarea>

                <label class="flex cursor-pointer items-center justify-between rounded-2xl bg-neutral-100 px-4 py-3 text-sm">
                    <span>Donasi sebagai <b>Hamba Allah</b></span>
                    <input type="checkbox" name="is_anonymous" value="1" class="peer sr-only">
                    <span class="relative h-6 w-11 rounded-full bg-neutral-300 transition peer-checked:bg-brand after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition peer-checked:after:translate-x-5"></span>
                </label>

                <p id="donate-msg" class="text-sm"></p>
                <button id="donate-btn" class="w-full rounded-full bg-brand py-4 font-medium text-white shadow-xl shadow-brand/30 transition duration-300 hover:bg-blue-600 disabled:opacity-60">Lanjut ke pembayaran</button>

                <div class="flex items-center justify-center gap-2 text-xs text-neutral-500">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg>
                    Pembayaran aman via Midtrans: QRIS, transfer bank, e-wallet
                </div>
            </form>
        </div>
    </aside>
</div>

{{-- CTA mobile --}}
<div class="fixed inset-x-3 bottom-3 z-30 lg:hidden">
    <a href="#donasi" class="flex items-center justify-between rounded-full bg-ink py-2 pl-6 pr-2 text-white shadow-2xl">
        <span class="text-sm"><b>{{ $campaign->percent }}%</b> terkumpul</span>
        <span class="rounded-full bg-brand px-6 py-3 text-sm font-medium">Donasi sekarang</span>
    </a>
</div>

<script src="{{ config('midtrans.snap_js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
(() => {
    const f = document.getElementById('donate-form'), b = document.getElementById('donate-btn'), m = document.getElementById('donate-msg');
    const col = document.getElementById('p-collected'), bar = document.getElementById('p-bar'), pct = document.getElementById('p-percent');
    const list = document.getElementById('donor-list'), cnt = document.getElementById('p-donors');
    const view = document.getElementById('amount-view'), amt = document.getElementById('amount');
    const chips = [...document.querySelectorAll('[data-chip]')];
    const syncTpl = @json(route('donations.sync', '__ID__'));
    const money = n => 'Rp ' + Math.round(n).toLocaleString('id-ID');
    let shown = {{ (int) $campaign->collected }}, donors = {{ (int) $donorCount }};

    const say = (t, ok) => { m.textContent = t; m.className = 'text-sm ' + (ok ? 'text-emerald-700' : 'text-red-600'); };

    const setAmount = n => {
        amt.value = n || '';
        chips.forEach(c => {
            const on = +c.dataset.chip === n;
            c.classList.toggle('bg-brand', on); c.classList.toggle('text-white', on); c.classList.toggle('ring-brand', on);
            c.classList.toggle('bg-white', !on);
        });
        b.textContent = n >= 10000 ? 'Donasi ' + money(n) : 'Lanjut ke pembayaran';
    };
    chips.forEach(c => c.addEventListener('click', () => { const n = +c.dataset.chip; view.value = n.toLocaleString('id-ID'); setAmount(n); }));
    view.addEventListener('input', () => {
        const n = parseInt(view.value.replace(/\D/g, ''), 10) || 0;
        view.value = n ? n.toLocaleString('id-ID') : ''; setAmount(n);
    });
    f.addEventListener('reset', () => setTimeout(() => { view.value = ''; setAmount(0); }));

    const tween = to => {
        const from = shown, t0 = performance.now(); shown = to;
        (function tick(t) { const p = Math.min(1, (t - t0) / 900);
            col.textContent = money(from + (to - from) * (1 - Math.pow(1 - p, 3))); if (p < 1) requestAnimationFrame(tick); })(t0);
    };
    const addDonor = d => {
        list.querySelector('[data-empty]')?.remove();
        const li = document.createElement('li'), av = document.createElement('span'), box = document.createElement('div'), line = document.createElement('p'), nm = document.createElement('b'), am = document.createElement('b');
        li.className = 'flex gap-3 rounded-2xl bg-white p-4 ring-1 ring-black/5';
        av.className = 'grid h-10 w-10 shrink-0 place-items-center rounded-full bg-lime-sp font-semibold'; av.textContent = (d.name || '?').charAt(0).toUpperCase();
        box.className = 'min-w-0 text-sm'; nm.textContent = d.name; am.className = 'text-brand'; am.textContent = money(d.amount);
        line.append(nm, ' berdonasi ', am); box.append(line);
        if (d.message) { const p = document.createElement('p'); p.className = 'mt-1 text-neutral-600'; p.textContent = d.message; box.append(p); }
        li.append(av, box); list.prepend(li);
        donors++; cnt.textContent = '(' + donors + ')';
    };
    // Cek status ke server tiap 4 detik (maks. 1 menit): progres naik begitu pembayaran terkonfirmasi.
    const sync = async orderId => {
        for (let i = 0; i < 15; i++) {
            try {
                const j = await (await fetch(syncTpl.replace('__ID__', orderId), { headers: { Accept: 'application/json' } })).json();
                if (j.collected !== shown) { tween(j.collected); bar.style.setProperty('--w', j.percent + '%'); pct.textContent = j.percent + '%'; }
                if (j.status === 'paid') { addDonor(j.donor); say('Terima kasih! Donasimu sudah masuk.', true); return; }
                if (j.status === 'failed' || j.status === 'expired') { say('Pembayaran tidak selesai.'); return; }
            } catch (e) {}
            await new Promise(r => setTimeout(r, 4000));
        }
    };
    const finish = (orderId, msg) => { f.reset(); b.disabled = false; say(msg, true); sync(orderId); };

    f.addEventListener('submit', async e => {
        e.preventDefault(); say('');
        if ((+amt.value || 0) < 10000) { say('Nominal minimal Rp 10.000.'); view.focus(); return; }
        b.disabled = true;
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

    const cp = document.getElementById('copy-link');
    cp.addEventListener('click', async () => {
        try { await navigator.clipboard.writeText(location.href.split('#')[0]); cp.textContent = 'Tersalin'; setTimeout(() => cp.textContent = 'Salin tautan', 1800); } catch (e) {}
    });
})();
</script>
@endsection
