@php
    $tints = ['bencana' => 'from-sky-100 to-sky-200', 'pendidikan' => 'from-amber-100 to-amber-200', 'kesehatan' => 'from-rose-100 to-rose-200', 'kemanusiaan' => 'from-emerald-100 to-emerald-200'];
@endphp
<article class="reveal" style="--d: {{ ($i ?? 0) * 110 }}ms">
  <div class="group flex h-full flex-col rounded-[2rem] bg-neutral-100 p-2.5 transition duration-300 ease-sp hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand/20">
    <a href="{{ route('campaigns.show', $c) }}" class="relative grid h-52 place-items-center overflow-hidden rounded-3xl bg-gradient-to-br {{ $tints[$c->category->slug] ?? 'from-neutral-100 to-neutral-200' }}">
        <span class="absolute -left-10 -top-10 h-40 w-40 rounded-full bg-white/50"></span>
        <span class="absolute -bottom-12 -right-8 h-44 w-44 rounded-full bg-white/40"></span>
        @if($c->image_url)<img src="{{ $c->image_url }}" alt="{{ $c->title }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover transition duration-500 group-hover:scale-105">@else
        <span class="ic relative transition duration-700 ease-sp group-hover:scale-110">@include('partials.icon', ['name' => $c->category->icon_name, 'class' => 'h-28 w-28 drop-shadow-xl'])</span>
        @endif
        <div class="absolute left-3 top-3 flex gap-1.5 text-xs font-medium">
            @if($c->is_urgent)<span class="rounded-full bg-ink px-3 py-1 text-white">Mendesak</span>@endif
            @if($c->is_verified)<span class="rounded-full bg-white px-3 py-1">Terverifikasi</span>@endif
        </div>
        <span class="absolute bottom-3 right-3 rounded-full bg-white/90 px-3 py-1 text-xs font-medium">{{ $c->days_left }} hari lagi</span>
    </a>
    <div class="flex flex-1 flex-col gap-3 p-4">
        <span class="text-xs text-neutral-500">{{ $c->category->name }} · {{ $c->organizer }}</span>
        <h3 class="font-display text-xl font-extrabold leading-tight">{{ $c->title }}</h3>
        <div class="mt-auto">
            <div class="h-2.5 overflow-hidden rounded-full bg-white"><div class="bar h-full rounded-full bg-gradient-to-r from-brand to-sky-400" style="--w: {{ $c->percent }}%"></div></div>
            <div class="mt-2 flex items-baseline justify-between text-sm">
                <b>Rp {{ number_format($c->collected, 0, ',', '.') }}</b>
                <span class="font-medium text-brand">{{ $c->percent }}%</span>
            </div>
        </div>
        <a href="{{ route('campaigns.show', $c) }}" class="rounded-full bg-ink py-3 text-center text-sm font-medium text-white transition duration-300 ease-sp hover:bg-brand">Lihat detail</a>
    </div>
  </div>
</article>
