<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk admin · Sahabat Peduli</title>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { fontFamily: { sans: ['Rubik', 'sans-serif'] }, colors: { brand: '#3b8eff' } } } }</script>
</head>
<body class="grid min-h-screen place-items-center bg-[#d9f76d] p-4 font-sans">
<form method="post" action="{{ route('admin.login.post') }}" class="w-full max-w-sm space-y-4 rounded-3xl bg-white p-8 shadow-xl">
    @csrf
    <h1 class="text-2xl font-bold">Masuk admin</h1>
    <input name="email" type="email" value="{{ old('email') }}" placeholder="Email" required autofocus class="w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
    <input name="password" type="password" placeholder="Password" required class="w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
    @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    <button class="w-full rounded-full bg-brand py-3 font-medium text-white">Masuk</button>
</form>
</body>
</html>
