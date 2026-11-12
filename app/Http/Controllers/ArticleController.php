<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return view('articles.index', ['articles' => Article::published()->latest('published_at')->paginate(9)]);
    }

    public function show(Article $article)
    {
        // Draft hanya bisa dilihat admin (untuk pratinjau).
        abort_unless($article->published_at?->lte(now()) || auth()->user()?->is_admin, 404);

        $more = Article::published()->where('id', '!=', $article->id)->latest('published_at')->take(3)->get();

        return view('articles.show', compact('article', 'more'));
    }
}
