@extends('admin.layout')
@php $edit = $article->exists; $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand'; @endphp
@section('title', $edit ? 'Ubah berita' : 'Tulis berita')
@section('content')
<h1 class="mb-6 text-2xl font-bold">{{ $edit ? 'Ubah berita' : 'Tulis berita' }}</h1>
<form method="post" enctype="multipart/form-data" class="grid max-w-3xl gap-5 rounded-2xl bg-white p-6"
      action="{{ $edit ? route('admin.articles.update', $article) : route('admin.articles.store') }}">
    @csrf @if($edit) @method('PUT') @endif
    @if($errors->any())<div class="rounded-xl bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif

    <label><span class="mb-1 block text-sm font-medium">Judul</span>
        <input name="title" value="{{ old('title', $article->title) }}" required class="{{ $in }}"></label>

    <div>
        <span class="mb-1 block text-sm font-medium">Gambar sampul (jpg, png, webp, maks 3 MB)</span>
        <div class="flex items-center gap-4">
            <img id="preview" src="{{ $article->image_url }}" alt="" class="h-24 w-36 rounded-xl bg-neutral-100 object-cover {{ $article->image_url ? '' : 'hidden' }}">
            <div class="space-y-2">
                <input type="file" name="image" accept="image/*" onchange="const f=this.files[0];if(f){const p=document.getElementById('preview');p.src=URL.createObjectURL(f);p.classList.remove('hidden')}" class="text-sm">
                @if($edit && $article->image)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remove_image" value="1"> Hapus gambar saat ini</label>@endif
            </div>
        </div>
    </div>

    <label><span class="mb-1 block text-sm font-medium">Ringkasan (opsional, kosong = diambil dari awal isi)</span>
        <input name="excerpt" value="{{ old('excerpt', $article->excerpt) }}" maxlength="255" class="{{ $in }}"></label>

    <label><span class="mb-1 block text-sm font-medium">Isi berita</span>
        <textarea name="body" rows="14" required class="{{ $in }}">{{ old('body', $article->body) }}</textarea>
        <span class="mt-1 block text-xs text-neutral-500">Pisahkan paragraf dengan satu baris kosong.</span></label>

    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="publish" value="1" @checked(old('publish', $edit ? (bool) $article->published_at : true))> Terbitkan (tidak dicentang = simpan sebagai draft)</label>

    <div class="flex gap-3">
        <button class="rounded-full bg-brand px-7 py-3 text-sm font-medium text-white">{{ $edit ? 'Simpan perubahan' : 'Simpan berita' }}</button>
        <a href="{{ route('admin.articles.index') }}" class="rounded-full px-7 py-3 text-sm ring-1 ring-black/10">Batal</a>
    </div>
</form>
@endsection
