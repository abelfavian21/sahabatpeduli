<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') · Sahabat Peduli</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Rubik', 'sans-serif'] }, colors: { brand: '#3b8eff', lime: { sp: '#d9f76d' } } } } }</script>
</head>
<body class="bg-neutral-100 font-sans text-neutral-900">
<div class="min-h-screen lg:grid lg:grid-cols-[15rem_1fr]">
    <aside class="bg-white p-4 lg:sticky lg:top-0 lg:h-screen">
        <a href="{{ route('admin.dashboard') }}" class="mb-6 block text-lg font-bold">Sahabat Peduli <span class="block text-xs font-normal text-neutral-500">Panel admin</span></a>
        <nav class="flex gap-2 text-sm lg:flex-col">
            @foreach([['admin.dashboard', 'Dashboard'], ['admin.campaigns.index', 'Kampanye'], ['admin.requests.index', 'Pengajuan'], ['admin.categories.index', 'Kategori'], ['admin.articles.index', 'Berita'], ['admin.donations.index', 'Donasi']] as [$r, $l])
                <a href="{{ route($r) }}" class="rounded-xl px-4 py-2.5 transition {{ request()->routeIs(str_replace('.index', '.*', $r)) ? 'bg-brand text-white' : 'hover:bg-neutral-100' }}">{{ $l }}@if($r === 'admin.requests.index' && ($n = \App\Models\CampaignRequest::where('status', 'pending')->count()))<span class="ml-2 rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">{{ $n }}</span>@endif</a>
            @endforeach
            <a href="{{ route('home') }}" target="_blank" class="rounded-xl px-4 py-2.5 hover:bg-neutral-100">Lihat situs</a>
        </nav>
        <form method="post" action="{{ route('admin.logout') }}" class="mt-4">@csrf
            <button class="w-full rounded-xl px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50">Keluar</button>
        </form>
    </aside>
    <main class="p-4 sm:p-8">
        @if(session('ok'))<p class="mb-4 rounded-xl bg-lime-sp px-4 py-3 text-sm">{{ session('ok') }}</p>@endif
        @yield('content')
    </main>
</div>
</body>
</html>
