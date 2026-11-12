@extends('admin.layout')
@section('title', 'Kategori')
@section('content')
<h1 class="text-2xl font-bold">Kategori</h1>
<p class="mb-6 mt-1 text-sm text-neutral-500">Foto tampil di bagian "Bidang kepedulian" beranda. Kosong = otomatis memakai foto kampanye aktif terbaru di kategori itu.</p>
<div class="grid gap-4 xl:grid-cols-2">
@foreach($categories as $c)
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.categories.update', $c) }}" class="space-y-4 rounded-2xl bg-white p-5">
        @csrf @method('PUT')
        <h2 class="text-lg font-semibold">{{ $c->name }}</h2>
        <div class="flex items-center gap-4">
            <img id="pv{{ $c->id }}" src="{{ $c->image ? \Illuminate\Support\Facades\Storage::disk('public')->url($c->image) : '' }}" alt="" class="h-24 w-20 rounded-xl bg-neutral-100 object-cover {{ $c->image ? '' : 'hidden' }}">
            <div class="space-y-2 text-sm">
                <input type="file" name="image" accept="image/*" onchange="const f=this.files[0];if(f){const p=document.getElementById('pv{{ $c->id }}');p.src=URL.createObjectURL(f);p.classList.remove('hidden')}">
                @if($c->image)<label class="flex items-center gap-2"><input type="checkbox" name="remove_image" value="1"> Hapus foto</label>@endif
            </div>
        </div>
        <input name="description" value="{{ old('description', $c->description) }}" maxlength="200" placeholder="Deskripsi singkat (opsional)" class="w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand">
        <button class="rounded-full bg-brand px-6 py-2.5 text-sm font-medium text-white">Simpan</button>
    </form>
@endforeach
</div>
@endsection
