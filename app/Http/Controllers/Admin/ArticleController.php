<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        return view('admin.articles.index', ['articles' => Article::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $r)
    {
        $d = $this->data($r);
        $d['slug'] = $this->uniqueSlug($d['title']);
        $d['published_at'] = $r->boolean('publish') ? now() : null;
        if ($r->hasFile('image')) {
            $d['image'] = $r->file('image')->store('articles', 'public');
        }
        Article::create($d);

        return redirect()->route('admin.articles.index')->with('ok', 'Berita disimpan.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $r, Article $article)
    {
        $d = $this->data($r);
        $d['published_at'] = $r->boolean('publish') ? ($article->published_at ?? now()) : null;
        if ($r->hasFile('image')) {
            $this->deleteFile($article->image);
            $d['image'] = $r->file('image')->store('articles', 'public');
        } elseif ($r->boolean('remove_image')) {
            $this->deleteFile($article->image);
            $d['image'] = null;
        }
        $article->update($d);

        return redirect()->route('admin.articles.index')->with('ok', 'Berita diperbarui.');
    }

    public function destroy(Article $article)
    {
        $this->deleteFile($article->image);
        $article->delete();

        return back()->with('ok', 'Berita dihapus.');
    }

    private function data(Request $r): array
    {
        $d = $r->validate([
            'title' => 'required|string|max:160',
            'excerpt' => 'nullable|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);
        unset($d['image']);
        $d['excerpt'] = $d['excerpt'] ?: Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($d['body']))), 160);

        return $d;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        for ($i = 2; Article::where('slug', $slug)->exists(); $i++) {
            $slug = "{$base}-{$i}";
        }

        return $slug;
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
