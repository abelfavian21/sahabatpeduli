<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sahabat Peduli') · sahabatpeduli.id</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,800&family=Rubik:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- Dev only. Produksi: pindah ke Vite + Tailwind. --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['Rubik', 'system-ui', 'sans-serif'], display: ['"Bricolage Grotesque"', 'Rubik', 'sans-serif'] },
            colors: { lime: { sp: '#d9f76d', deep: '#c4ee2f' }, brand: '#3b8eff', ink: '#14161a' }
        } } }
    </script>
    <style>
        body {
            background-image:
                radial-gradient(60rem 36rem at 0% 0%, rgba(217, 247, 109, .5), transparent 65%),
                radial-gradient(50rem 36rem at 100% 12%, rgba(59, 142, 255, .13), transparent 65%),
                url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='56' height='56'%3E%3Cpath d='M28 22v12M22 28h12' stroke='%233b8eff' stroke-opacity='.17' stroke-width='2.5' stroke-linecap='round'/%3E%3Cg fill='%23a8d400' fill-opacity='.35'%3E%3Ccircle cx='0' cy='0' r='1.8'/%3E%3Ccircle cx='56' cy='0' r='1.8'/%3E%3Ccircle cx='0' cy='56' r='1.8'/%3E%3Ccircle cx='56' cy='56' r='1.8'/%3E%3C/g%3E%3C/svg%3E");
            background-repeat: no-repeat, no-repeat, repeat;
        }
        .bar { width: var(--w); }
        .ease-sp { transition-timing-function: cubic-bezier(.2,.8,.2,1); }
        .morph { border-radius: 60% 40% 55% 45% / 50% 55% 45% 50%; }
        .w { display: inline-block; overflow: hidden; vertical-align: top; padding-bottom: .12em; margin-bottom: -.12em; }
        .w > span { display: inline-block; }
        @media (prefers-reduced-motion: no-preference) {
            .reveal { opacity: 0; transform: translateY(14px); transition: opacity .6s ease var(--d,0ms), transform .6s cubic-bezier(.2,.8,.2,1) var(--d,0ms); }
            .reveal.in { opacity: 1; transform: none; }
            .reveal .bar { width: 0; transition: width 1s cubic-bezier(.2,.8,.2,1) .2s; }
            .reveal.in .bar { width: var(--w); }
            .w > span { transform: translateY(115%); animation: up .7s cubic-bezier(.2,.8,.2,1) forwards; animation-delay: calc(var(--d,0ms) + 100ms); }
            .float { animation: float 8s ease-in-out infinite; }
        }
        @keyframes up { to { transform: none; } }
        @keyframes float { 50% { transform: translateY(-6px); } }
    </style>
</head>
<body class="overflow-x-hidden bg-white font-sans text-ink antialiased">

<div class="w-full px-3 pb-8 sm:px-6 lg:px-10">
    <header class="sticky top-3 z-40 mt-3 flex items-center justify-between gap-3 rounded-full bg-white/80 px-3 py-2 shadow-[0_8px_30px_rgba(20,22,26,.07)] ring-1 ring-black/5 backdrop-blur-xl">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-lg font-extrabold">
            <svg viewBox="0 0 36 36" class="h-10 w-10 transition duration-500 ease-sp hover:rotate-[20deg] hover:scale-110" aria-hidden="true">
                <circle cx="18" cy="18" r="18" fill="#c4ee2f"/><circle cx="12.5" cy="15" r="2.2" fill="#14161a"/><circle cx="23.5" cy="15" r="2.2" fill="#14161a"/>
                <path d="M10 22c2.5 4.5 13.5 4.5 16 0" stroke="#14161a" stroke-width="2.4" stroke-linecap="round" fill="none"/>
            </svg>
            <span class="hidden sm:inline">Sahabat Peduli</span>
        </a>
        <nav class="hidden gap-8 text-sm font-medium md:flex">
            @foreach([['Kampanye', route('campaigns.index')], ['Berita', route('articles.index')], ['Transparansi', route('home').'#transparansi'], ['Tentang', route('home').'#tentang'], ['Ajukan kampanye', route('requests.create')]] as [$l, $u])
                <a href="{{ $u }}" class="group relative py-1">{{ $l }}<span class="absolute inset-x-0 -bottom-0.5 h-0.5 origin-left scale-x-0 rounded bg-brand transition duration-300 ease-sp group-hover:scale-x-100"></span></a>
            @endforeach
        </nav>
        <a href="{{ route('campaigns.index') }}" class="rounded-full bg-ink px-6 py-2.5 text-sm font-medium text-white transition duration-300 ease-sp hover:bg-brand hover:shadow-lg hover:shadow-brand/40">Donasi sekarang</a>
    </header>

    @yield('content')

    <footer class="mt-20 flex flex-wrap justify-between gap-3 border-t border-neutral-200 pt-6 text-sm text-neutral-500">
        <span>© {{ date('Y') }} sahabatpeduli.id</span>
        <span>Ruang kita peduli ke sesama manusia.</span>
    </footer>
</div>

<script>
    const io = new IntersectionObserver(es => es.forEach(e => {
        if (!e.isIntersecting) return;
        e.target.classList.add('in'); io.unobserve(e.target);
    }), { threshold: .15 });
    document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>
