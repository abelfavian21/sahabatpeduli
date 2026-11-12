<article class="reveal" style="--d: {{ ($i ?? 0) * 100 }}ms">
    <a href="{{ route('articles.show', $a) }}" class="group flex h-full flex-col overflow-hidden rounded-[2rem] bg-white p-2.5 ring-1 ring-black/5 transition duration-300 ease-sp hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand/10">
        <div class="relative aspect-[16/10] overflow-hidden rounded-3xl bg-neutral-100">
            @if($a->image_url)
                <img src="{{ $a->image_url }}" alt="{{ $a->title }}" loading="lazy" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
            @else
                <div class="grid h-full w-full place-items-center bg-gradient-to-br from-lime-sp to-sky-200">@include('partials.icon', ['name' => 'people', 'class' => 'h-20 w-20'])</div>
            @endif
        </div>
        <div class="flex flex-1 flex-col p-4">
            <p class="text-xs text-neutral-500">{{ $a->published_at?->translatedFormat('d F Y') ?? 'Draft' }} · {{ $a->read_time }} menit baca</p>
            <h3 class="mt-2 font-display text-xl font-extrabold leading-tight">{{ $a->title }}</h3>
            <p class="mt-2 line-clamp-2 text-sm text-neutral-600">{{ $a->excerpt }}</p>
            <span class="mt-auto inline-flex items-center gap-2 pt-4 text-sm font-medium text-brand">
                Baca selengkapnya
                <svg viewBox="0 0 24 24" class="h-4 w-4 transition duration-300 ease-sp group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </span>
        </div>
    </a>
</article>
