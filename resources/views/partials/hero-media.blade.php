@php
    $tints = ['bencana' => 'from-sky-200 to-sky-400', 'pendidikan' => 'from-amber-200 to-amber-400', 'kesehatan' => 'from-rose-200 to-rose-400', 'kemanusiaan' => 'from-emerald-200 to-emerald-400'];
@endphp
<a href="{{ route('campaigns.show', $c) }}" class="group relative block h-full min-h-[15rem] overflow-hidden rounded-[2rem] bg-neutral-200">
    @if($c->video_url)
        <video src="{{ $c->video_url }}" autoplay muted loop playsinline preload="metadata" @if($c->image_url) poster="{{ $c->image_url }}" @endif class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"></video>
    @elseif($c->image_url)
        <img src="{{ $c->image_url }}" alt="{{ $c->title }}" class="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105">
    @else
        <div class="absolute inset-0 grid place-items-center bg-gradient-to-br {{ $tints[$c->category->slug] ?? 'from-neutral-200 to-neutral-300' }}">
            @include('partials.icon', ['name' => $c->category->icon_name, 'class' => ($big ?? false) ? 'h-40 w-40 -mt-16' : 'h-20 w-20 -mt-10'])
        </div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
    <div class="absolute left-4 top-4 flex gap-1.5 text-xs font-medium">
        @if($c->is_urgent)<span class="rounded-full bg-lime-deep px-3 py-1 text-ink">Mendesak</span>@endif
        <span class="rounded-full bg-white/90 px-3 py-1">{{ $c->category->name }}</span>
    </div>
    <div class="absolute inset-x-0 bottom-0 p-5 text-white">
        <h3 class="font-display font-extrabold leading-tight {{ ($big ?? false) ? 'text-2xl sm:text-3xl' : 'text-lg' }}">{{ $c->title }}</h3>
        <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-white/30"><div class="h-full rounded-full bg-lime-deep" style="width: {{ $c->percent }}%"></div></div>
        <div class="mt-2 flex items-center justify-between text-sm">
            <span><b>Rp {{ number_format($c->collected, 0, ',', '.') }}</b> <span class="text-white/70">· {{ $c->percent }}%</span></span>
            <span class="text-white/70">{{ $c->days_left }} hari lagi</span>
        </div>
    </div>
</a>
