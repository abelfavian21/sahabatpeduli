<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', ['categories' => Category::all()]);
    }

    public function update(Request $r, Category $category)
    {
        $d = $r->validate([
            'description' => 'nullable|string|max:200',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);
        unset($d['image']);

        if ($r->hasFile('image')) {
            $this->deleteFile($category->image);
            $d['image'] = $r->file('image')->store('categories', 'public');
        } elseif ($r->boolean('remove_image')) {
            $this->deleteFile($category->image);
            $d['image'] = null;
        }
        $category->update($d);

        return back()->with('ok', 'Kategori ' . $category->name . ' diperbarui.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
