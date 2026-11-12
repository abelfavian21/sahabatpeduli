@extends('admin.layout')
@php $edit = $campaign->exists; $in = 'w-full rounded-2xl bg-neutral-100 px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-brand'; @endphp
@section('title', $edit ? 'Ubah kampanye' : 'Tambah kampanye')
@section('content')
<h1 class="mb-6 text-2xl font-bold">{{ $edit ? 'Ubah kampanye' : 'Tambah kampanye' }}</h1>
<form method="post" enctype="multipart/form-data" class="grid max-w-4xl gap-5 rounded-2xl bg-white p-6 md:grid-cols-2"
      action="{{ $edit ? route('admin.campaigns.update', $campaign) : route('admin.campaigns.store') }}">
    @csrf @if($edit) @method('PUT') @endif

    @if($errors->any())<div class="rounded-xl bg-red-50 p-3 text-sm text-red-700 md:col-span-2">{{ $errors->first() }} ({{ $errors->count() }} kesalahan)</div>@endif

    <label class="md:col-span-2"><span class="mb-1 block text-sm font-medium">Judul</span>
        <input name="title" value="{{ old('title', $campaign->title) }}" required class="{{ $in }}"></label>

    <label><span class="mb-1 block text-sm font-medium">Kategori</span>
        <select name="category_id" required class="{{ $in }}">
            @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected(old('category_id', $campaign->category_id) == $cat->id)>{{ $cat->name }}</option>@endforeach
        </select></label>

    <label><span class="mb-1 block text-sm font-medium">Penggalang / penerima</span>
        <input name="organizer" value="{{ old('organizer', $campaign->organizer) }}" required class="{{ $in }}"></label>

    <label class="md:col-span-2"><span class="mb-1 block text-sm font-medium">Deskripsi singkat</span>
        <input name="excerpt" value="{{ old('excerpt', $campaign->excerpt) }}" maxlength="255" required class="{{ $in }}"></label>

    <label class="md:col-span-2"><span class="mb-1 block text-sm font-medium">Cerita lengkap</span>
        <textarea name="story" rows="8" required class="{{ $in }}">{{ old('story', $campaign->story) }}</textarea></label>

    <div class="md:col-span-2">
        <span class="mb-1 block text-sm font-medium">Gambar (jpg, png, webp, maks 3 MB)</span>
        <div class="flex items-center gap-4">
            <img id="preview" src="{{ $campaign->image_url }}" alt="" class="h-24 w-36 rounded-xl bg-neutral-100 object-cover {{ $campaign->image_url ? '' : 'hidden' }}">
            <div class="space-y-2">
                <input type="file" name="image" accept="image/*" onchange="const f=this.files[0];if(f){const p=document.getElementById('preview');p.src=URL.createObjectURL(f);p.classList.remove('hidden')}" class="text-sm">
                @if($edit && $campaign->image)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remove_image" value="1"> Hapus gambar saat ini</label>@endif
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <span class="mb-1 block text-sm font-medium">Video footage (opsional, mp4/webm, maks 20 MB)</span>
        @if($campaign->video_url)<video src="{{ $campaign->video_url }}" muted controls class="mb-2 h-32 rounded-xl bg-neutral-100"></video>@endif
        <input type="file" name="video" accept="video/mp4,video/webm" class="text-sm">
        @if($edit && $campaign->video)<label class="mt-2 flex items-center gap-2 text-sm"><input type="checkbox" name="remove_video" value="1"> Hapus video saat ini</label>@endif
        <p class="mt-1 text-xs text-neutral-500">Tampil di beranda dan halaman kampanye. Video diputar otomatis tanpa suara di beranda.</p>
    </div>

    <label><span class="mb-1 block text-sm font-medium">Target dana (Rp)</span>
        <input name="target" type="number" min="10000" value="{{ old('target', $campaign->target) }}" required class="{{ $in }}"></label>

    <label><span class="mb-1 block text-sm font-medium">Dana terkumpul (Rp)</span>
        <input name="collected" type="number" min="0" value="{{ old('collected', $campaign->collected ?? 0) }}" class="{{ $in }}"></label>

    <label><span class="mb-1 block text-sm font-medium">Batas waktu</span>
        <input name="deadline" type="date" value="{{ old('deadline', $campaign->deadline?->format('Y-m-d')) }}" required class="{{ $in }}"></label>

    <label><span class="mb-1 block text-sm font-medium">Status</span>
        <select name="status" class="{{ $in }}">
            @foreach(['draft' => 'Draft (tidak tampil)', 'active' => 'Aktif', 'closed' => 'Ditutup'] as $v => $l)<option value="{{ $v }}" @selected(old('status', $campaign->status) === $v)>{{ $l }}</option>@endforeach
        </select></label>

    <div class="flex gap-6 md:col-span-2">
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_urgent" value="1" @checked(old('is_urgent', $campaign->is_urgent))> Tandai mendesak</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $campaign->is_verified))> Terverifikasi</label>
    </div>

    <div class="flex gap-3 md:col-span-2">
        <button class="rounded-full bg-brand px-7 py-3 text-sm font-medium text-white">{{ $edit ? 'Simpan perubahan' : 'Buat kampanye' }}</button>
        <a href="{{ route('admin.campaigns.index') }}" class="rounded-full px-7 py-3 text-sm ring-1 ring-black/10">Batal</a>
    </div>
</form>
@endsection
